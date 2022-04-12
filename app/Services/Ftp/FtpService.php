<?php


namespace App\Services\Ftp;

use Illuminate\Support\Facades\Storage;


class FtpService implements FtpServiceInterface
{

    /**
     * @param $file
     * @return string
     */
    public function uploadFile($file) : string
    {
        return $this->storageFile($file);
    }

    private function storageFile($image) : string
    {
        # get s3 object make sure your key matches with
        # config/filesystem.php file configuration
        $s3 = Storage::disk('s3');

        # rename file name to random name
        $file_name = uniqid() .'.'. $image->getClientOriginalExtension();

        # define s3 target directory to upload file to
        $s3filePath = $file_name;

        # finally upload your file to s3 bucket
        $s3->put($s3filePath, file_get_contents($image), 'public');
        return $file_name;
    }
}
