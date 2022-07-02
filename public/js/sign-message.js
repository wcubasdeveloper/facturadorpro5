
  qz.security.setCertificatePromise(function(resolve, reject) {
      //Preferred method - from server
//        $.ajax("assets/signing/digital-certificate.txt").then(resolve, reject);

      //Alternate method 1 - anonymous
//        resolve();

      //Alternate method 2 - direct
      resolve("-----BEGIN CERTIFICATE-----\n" +
            "MIID9DCCAtygAwIBAgIJAPdXYKD3oGt9MA0GCSqGSIb3DQEBCwUAMIGOMQswCQYD\n"+
            "VQQGEwJQRTEQMA4GA1UECAwHVUNBWUFMSTERMA8GA1UEBwwIUFVDQUxMUEExDDAK\n"+
            "BgNVBAoMA1RFTDEMMAoGA1UECwwDVEVMMRowGAYDVQQDDBF0b2RvLWVuLWxpbmVh\n"+
            "LmNvbTEiMCAGCSqGSIb3DQEJARYTdGhlbmV3Nzc3QGdtYWlsLmNvbTAeFw0xODAy\n"+
            "MTMwNDEyMzFaFw00OTA4MDgwNDEyMzFaMIGOMQswCQYDVQQGEwJQRTEQMA4GA1UE\n"+
            "CAwHVUNBWUFMSTERMA8GA1UEBwwIUFVDQUxMUEExDDAKBgNVBAoMA1RFTDEMMAoG\n"+
            "A1UECwwDVEVMMRowGAYDVQQDDBF0b2RvLWVuLWxpbmVhLmNvbTEiMCAGCSqGSIb3\n"+
            "DQEJARYTdGhlbmV3Nzc3QGdtYWlsLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEP\n"+
            "ADCCAQoCggEBAMJnB2YAxNj2pHo0a17Epaz+N9tnJ9zRTctxiqjb2N7LHNEIPTpv\n"+
            "4wmRNfybhJEKy0vaq+544eJm9eglJWlEg0Iq5OQrNwxaL2yKOIEi7NyXabWi8CFf\n"+
            "l3rUMh+EGu8hwunma8rlhH7KDJ5VZ4NGpnaPaQ5wEnswt3H3JeXAyRQNrbHe7HJB\n"+
            "+YfG/VFthLFNEhh35NDy8c7p0WN/pVp5BRspJOV9R8jrTXbBSC8s1R8Em4nruQhP\n"+
            "7COcdnAiQvDOIse3H78KhfMPPjGFGEIOoj1LnLjenw0BxjDQxUVrVbNGUx2Z0fvv\n"+
            "Y9Y7y4L2CeY8mFtWaXTtEbnKQBSnGtbCRXECAwEAAaNTMFEwHQYDVR0OBBYEFBJ5\n"+
            "wYUHD4TavETKkrJoypy2XhBRMB8GA1UdIwQYMBaAFBJ5wYUHD4TavETKkrJoypy2\n"+
            "XhBRMA8GA1UdEwEB/wQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBAE1q2eq1BdT8\n"+
            "jReUZpkOqAvkA1bxOQH2eTiFBYlFPk4ktylqXwcawsdNTlFdHvPTTcbqreDcIut9\n"+
            "PPqZZ3VzbdRC9mGWxC7NZOTK7DW9AoV3e6LweMdYqHPdZHg+ayxCqsmSX6AOTn0k\n"+
            "VW7g5/Av48PEX+UouPUFhujpOBNrKsU9DH9JrU8jbIAtbaofnD4zbYERi8Dyc88p\n"+
            "5E8KclUBOdega3CEI/VgHwc5iw8TvVXu6WO3B5WhkGBKNVc1mpwndM8GDBf0YwsB\n"+
            "SbMOGwAbAlyaS1F5X3+qlaDpQdH7XhisLxfCHs52Nz9dLG+sNAhacIDzBZlDB/sU\n"+
            "oW/Zf3zj9Ag=\n"+
            "-----END CERTIFICATE-----\n");
  });

  privateKey = "-----BEGIN PRIVATE KEY-----\n" +
            "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDCZwdmAMTY9qR6\n"+
            "NGtexKWs/jfbZyfc0U3LcYqo29jeyxzRCD06b+MJkTX8m4SRCstL2qvueOHiZvXo\n"+
            "JSVpRINCKuTkKzcMWi9sijiBIuzcl2m1ovAhX5d61DIfhBrvIcLp5mvK5YR+ygye\n"+
            "VWeDRqZ2j2kOcBJ7MLdx9yXlwMkUDa2x3uxyQfmHxv1RbYSxTRIYd+TQ8vHO6dFj\n"+
            "f6VaeQUbKSTlfUfI6012wUgvLNUfBJuJ67kIT+wjnHZwIkLwziLHtx+/CoXzDz4x\n"+
            "hRhCDqI9S5y43p8NAcYw0MVFa1WzRlMdmdH772PWO8uC9gnmPJhbVml07RG5ykAU\n"+
            "pxrWwkVxAgMBAAECggEAXU+YxIQ/+ChDAIlitCVNpMCNTRmxj5NDdRB1zuFfsmjp\n"+
            "1wfOY9tKrc/uiuaW9gupUyqN9jQ9sC9df2U9FM8W9c6i+UYo8RvkwYOC5bE+4g8n\n"+
            "ZVDlVA+PJRzvRiNhzkB1T1ITkVsjgrw23FUAD4n84tGpSo3OwSS8GM7ZePNVUPL9\n"+
            "PqxqKIyiVp4enWUis6wn0bm4DAUu7oNqcMrjaToHhlUi0UMYqgFwOHQ3MPU0wdhy\n"+
            "+ncyN4MhIeX1LlirlmVP8AvYKwKKgfRb84y1jPTHfO+dNhMEUN5r9QfNAkwuazJv\n"+
            "cmlYdNsxjuW6fgsTZm3d0qASZiV+H01AJQo1oXuyAQKBgQDt7lGpk/O9H0Bt4S7K\n"+
            "ap7psYmMez9G6BVXuxaknNHY9XsKvwR/ZeIuvCcuyZ3lUqgdgEInbGNCmpVUTTS3\n"+
            "tgfKD25ZTofGxOI+koBx+0EFciARKc+xaNqXaUnJ1nugA2jhWLvwVrmv5FF2VhhW\n"+
            "OVljX7BqI+f1eov+6kHu3qxR0QKBgQDRKnX0f7Oz35tLVa8v6e5+W4qdVOcE88Ad\n"+
            "NRlODUGaWa1YFgiXc6Dcb9eFMUaoD4HMfVoirEd6ZjIzEb/l2MKqWaPYyLB4dNDF\n"+
            "ML3a/V7XXaWVMmVom9aLNUu8jKvhikKUUxkN+M6fWMMi8M92+B0oAcLQajVPYV8O\n"+
            "Ohc4OkEBoQKBgGwM6GT8XZorURUVSCyAUv6Js49qgQfwaZDX06aZ2OqQQHpW2PIK\n"+
            "ELdslta2lNAJw3LyRhilLkaW8O3BygkLz2nBrDk+Yoav7pa/7TjWA2c3trxUoo9M\n"+
            "sMhF9k6E6st2APElXOP+XoE0TJJS8uZlUOTCFdl9yN8/8ceoFp0l3lehAoGAchhs\n"+
            "UVubheHSjyyFLGi53JlIqnvWrL/dqtD9JbNbdru2L9eNBjhfpf8oHBJ+DUywLACw\n"+
            "uzsonl7CwVLMT6+GuG+/TZBjmsF15CqrVZpiMq51lUXxRTfEtxjyYD6Hv7awjMIr\n"+
            "Z5Cx/P/pKdUcBjRfiyQyxYc53zwpItSTN+um7CECgYANQJI4KOua/F48IDk+32Fg\n"+
            "URLSnZjRDh+LBWWj8EivEum4Q/J1kLHljnG77BQO9gUdXe6tqdfWrAAxABqAvHdD\n"+
            "GLQvKqg0GDz5IVyR32wX7dY35guqzEcorKyNHHwPrcheKTv3IR/Fp/tQjUpXGaR/\n"+
            "0bOVUb468IFv2kDlkY3DOA==\n"+
            "-----END PRIVATE KEY-----\n";


    qz.security.setSignaturePromise(function(toSign) {
        return function(resolve, reject) {
            try {
                var pk = KEYUTIL.getKey(privateKey);
                var sig = new KJUR.crypto.Signature({"alg": "SHA1withRSA"});
                sig.init(pk);
                sig.updateString(toSign);
                var hex = sig.sign();
                resolve(stob64(hextorstr(hex)));
            } catch (err) {
                reject(err);
            }
        };
    });
