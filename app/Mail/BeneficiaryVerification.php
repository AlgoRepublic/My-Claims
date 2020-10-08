<?php

namespace App\Mail;

use App\Beneficiaries;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class BeneficiaryVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $beneficiary;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($beneficiary)
    {
        $this->beneficiary = $beneficiary;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $currentData = $this->beneficiary;
        $data = array(
            'policyholder_idn' => $currentData['policyholder_idn'],
            //'beneficiary_idn' => $currentData['beneficiary_idn'],
            'email_preference' => $currentData['email_preference']
        );

        return $this->from('mashood.ali@algorepublic.com')
        ->view('emails.beneficiary_verification')
            ->attach($this->getPath($currentData['beneficiary_identity_file']),[
                'as' => 'Beneficiary IDN.' . $currentData['ben_file_ext']
            ])
            ->attach($this->getPath($currentData['policyholder_death_proof_file']),[
                'as' => 'PolicyHolder Death Proof.' . $currentData['pol_file_ext']
            ])
            ->with($data);
    }

    public function getPath($name) {
        return storage_path("app\public\beneficiaries_uploads/$name");
    }
}
