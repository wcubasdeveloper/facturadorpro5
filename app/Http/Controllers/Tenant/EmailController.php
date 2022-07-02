<?php

    namespace App\Http\Controllers\Tenant;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\EmailSendLog;
    use Exception;
    use Illuminate\Support\Facades\Mail;
    use Log;
    use Swift_RfcComplianceException;

    class EmailController extends Controller
    {
        /** @var string|null */
        protected $error;
        /** @var string|null */
        protected $email;
        /** @var array */
        protected $arrayEmail;
        /** @var int|null */
        protected $error_code;
        /** @var int|null */
        protected $id;
        /** @var int|null */
        protected $line;
        /** @var bool|null */
        protected $hasEror;
        /** @var string|null */
        protected $file_line;
        /** @var object|null */
        protected $type;

        /**
         * EmailController constructor.
         */
        public function __construct()
        {
            $this->id = 0;
            $this->email = null;
            $this->type = null;
            $this->file_line = null;
            $this->error = null;
            $this->error_code = null;
            $this->hasEror = false;
            $this->line = __LINE__;
        }


        /**
         * @param      $email
         * @param      $mailable
         * @param int  $id
         * @param null $type
         *
         * @return bool
         */
        public static function SendMail($email, $mailable, $id = 0, $type = null): bool
        {
            $sendit = new self();
            $mail = explode(';', str_replace([',', ' '], [';', ''], $email));
            $mails = [];
            $sendit
                ->setType($type)
                ->setId($id);
            if (!empty($mail) && count($mail) > 0) {
                foreach ($mail as $email) {
                    $email = trim($email);
                    if (!empty($email)) {
                        $mails[] = $email;
                    }
                }
                $email= implode(';',$mails);

            }
            $sendit
                ->setArrayEmail(explode(';',$email))
                // ->setEmail(str_replace([';', '  '], [', ', ' '], $email))
                ->SendAMail($mailable);


            return true;
        }

        /**
         * @param $mailable
         *
         * @return bool
         */
        protected function SendAMail($mailable)
        {
            Configuration::setConfigSmtpMail();
            $ret = true;
            try {
                Mail::to($this->getArrayEmail())->send($mailable);
                $this->saveModel($ret);
            } catch (Swift_RfcComplianceException $e) {
                $ret = false;
                $this
                    ->setError($e->getMessage())
                    ->setErrorCode($e->getCode())
                    ->setHasEror(!$ret)
                    ->setLine(__LINE__);
                $this->saveError();
                $this->saveModel();
            } catch (Exception $e) {
                $ret = false;
                $this
                    ->setError($e->getMessage())
                    ->setErrorCode($e->getCode())
                    ->setHasEror(!$ret)
                    ->setLine(__LINE__);
                $this->saveError();
                $this->saveModel();
            }
            return $ret;
        }

        /**
         * @return string|null
         */
        public function getEmail(): ?string
        {
            return $this->email;
        }

        /**
         * @return array
         */
        public function getArrayEmail(): ?array
        {
            return $this->arrayEmail;
        }

        /**
         * @param string|null $email
         *
         * @return EmailController
         */
        public function setEmail(?string $email): EmailController
        {
            $this->email = $email;
            return $this;
        }
        /**
         * @param  array $email
         *
         * @return EmailController
         */
        public function setArrayEmail( $email = []): EmailController
        {
            $this->arrayEmail = $email;
            return $this;
        }

        /**
         * @param false $sendit
         *
         * @return $this
         */
        public function saveModel($sendit = false)
        {


            $e = new EmailSendLog();
            $e->setRelationId($this->getId());
            if (is_numeric($this->type)) {
                $e
                    ->setModelByType($this->getType())
                    ->setEmail(implode(',',$this->getArrayEmail()))
                    ->setSendit((bool)$sendit)
                    ->push();


            } else {
                // No guardar eventos no mapeados
                /*
                $e->setModelByType(0)
                    ->setFileLine($this->file_line);
                $e->setEmail($this->getEmail())
                    ->setSendit((bool)$sendit)
                    ->push();
                */
            }


            return $this;

        }

        /**
         * @return int|null
         */
        public function getId(): ?int
        {
            return (int)$this->id;
        }

        /**
         * @param int|null $id
         *
         * @return EmailController
         */
        public function setId(?int $id): EmailController
        {
            $this->id = (int)$id;
            return $this;
        }

        /**
         * @return object|null
         */
        public function getType()
        {
            return $this->type;
            /*
            $class = $this->type;
            if (!empty($class)) {
                try {
                    $class = get_class($this->model);
                } catch (ErrorException $e) {
                    $class = 'Desconocida';
                }
            }
            return $class;
            */
        }

        /**
         * @param int|string|null $type
         *
         * @return EmailController
         */
        public function setType($type)
        {
            if (is_numeric($type)) {
                $this->type = $type;
            } else {
                $this->file_line = $type;
            }
            return $this;
        }

        public function saveError()
        {
            Log::channel('emails')->error(
                "Codigo : " . $this->getErrorCode() . "\n" .
                "Mensaje : " . $this->getError() . "\n" .
                "Linea : " . $this->getLine() . "\n" .
                "\n"
            );
        }

        /**
         * @return int|null
         */
        public function getErrorCode(): ?int
        {
            return $this->error_code;
        }

        /**
         * @param int|null $error_code
         *
         * @return EmailController
         */
        public function setErrorCode(?int $error_code): EmailController
        {
            $this->error_code = $error_code;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getError(): ?string
        {
            return $this->error;
        }

        /**
         * @param string|null $error
         *
         * @return EmailController
         */
        public function setError(?string $error): EmailController
        {
            $this->error = $error;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getLine(): ?int
        {
            return $this->line;
        }

        /**
         * @param int|null $line
         *
         * @return EmailController
         */
        public function setLine(?int $line): EmailController
        {
            $this->line = $line;
            return $this;
        }

        /**
         * @return bool|null
         */
        public function getHasEror(): ?bool
        {
            return (bool)$this->hasEror;
        }

        /**
         * @param bool|null $hasEror
         *
         * @return EmailController
         */
        public function setHasEror(?bool $hasEror): EmailController
        {
            $this->hasEror = (bool)$hasEror;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getFileLine(): ?string
        {
            return $this->file_line;
        }

        /**
         * @param string|null $file_line
         *
         * @return EmailController
         */
        public function setFileLine(?string $file_line): EmailController
        {
            $this->file_line = $file_line;
            return $this;
        }
        //

    }
