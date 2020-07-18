<?php

namespace Love4Work\Laravel\Mail\Message;

use Love4Work\Laravel\Mail\Contracts\DkimProvider;
use Swift_Signers_DKIMSigner;

trait AttachDkimTrait
{

    public function attachDkim(DkimProvider $dkimProvider)
    {
        if (!in_array(strtolower(config('mail.driver')), ['smtp', 'sendmail', 'log'])) return $this;

        $signer = new Swift_Signers_DKIMSigner(
            $dkimProvider->getPrivateKey(),
            $dkimProvider->getDomain(),
            $dkimProvider->getSelector(),
            $dkimProvider->getPassphrase(),
        );
        $signer->setHashAlgorithm($dkimProvider->getAlgorithm());

        if ($identity = $dkimProvider->getIdentity()) {
            $signer->setSignerIdentity($identity);
        }

        $this->swift->attachSigner($signer);

        return $this;
    }

}


