<?php

    namespace App\Rules;

    use Illuminate\Contracts\Validation\Rule;
    use function strlen;

    class SubdomainNotLatin implements Rule
    {
        /**
         * Create a new rule instance.
         *
         * @return void
         */
        public function __construct()
        {
            //
            $this->message ="Existen caracteres no validos.";
        }

        /**
         * @return mixed
         */
        public function getMessage()
        {
            return $this->message;
        }

        /**
         * @param mixed $message
         *
         * @return SubdomainNotLatin
         */
        public function setMessage($message)
        {
            $this->message = $message;
            return $this;
        }

        protected $message;

        /**
         * Get the validation error message.
         *
         * @return string
         */
        public function message()
        {
            return $this->message ;
        }

        /**
         * Determine if the validation rule passes.
         *
         * @param string $attribute
         * @param mixed  $value
         *
         * @return bool
         */
        public function passes($attribute, $value)
        {
            $subdomain = $value;
            $lngSub = strlen($subdomain);
            $newValue = strlen($this->cleanString(trim($value)));


            if (!preg_match("#^[a-zA-Z0-9]+$#", $value)) {
                $this->setMessage("No se permiten símbolos, solo números y letras");
                return false;
            }


            return $newValue === $lngSub;
            //
        }

        /**
         * Se reemplazan acentos, ñ, punto (.)
         * @param $str
         *
         * @return string
         */
        public function cleanString($str)
        {
            return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝñÑ.'), '');
        }
    }
