<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Google\Client;
use Google\Service\YouTube;
use Exception;
use Illuminate\Support\Facades\Session;
use Google\Service\Exception as GoogleServiceException;

class CheckYoutubeId implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($value)
    {
        function isValidByCoverImage($value){
            $url = 'https://img.youtube.com/vi/'.$value.'/0.jpg';
            if (@file_get_contents($url, 0, NULL, 0, 1)) {
                return true;
            }
            return false;
        }

        $apiKey = env('YOUTUBE_APP_ID');

        $client = new Client();
        $client->setDeveloperKey($apiKey);
        $service = new YouTube($client);

        try {
            $response = $service->videos->listVideos('contentDetails', ['id' => $value]);

            $result = array_filter($response->items, function($input) use ($value) {
                return ($input->id === $value);
            });

            return count($result)>0;

        }catch (GoogleServiceException $e) {
            Session::flash('youtube_api_error', $e->getMessage());
            return isValidByCoverImage($value);
        }
        catch(Exception $e) {
            Session::flash('youtube_api_error',$e->getMessage());
            return isValidByCoverImage($value);
        }


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Video URL is not valid.';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) !== true) {
            $fail('The :attribute is invalid.');
        }
    }
}
