<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NytController extends Controller
{
    /**
     * Gets information from the New York Times API.
     *
     * @return \Illuminate\Http\Response
     */
    function bestSellers(Request $request) {
        $url    = 'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json';

        $author = (string) $request->get('author');
        $isbn   = (string) $request->get('isbn');
        $title  = (string) $request->get('title');
        $offset = (int)    $request->get('offset');
        
        $params = [
            'api-key' => 'Gl4UeOmae5pFIgGqxInt8H6K4HGDDCxx'
        ];

        // I was not sure if you wanted me to validate the user inputs
        // it seemed from the problem that you did so I went ahead and added this
        // otherwise you could just send the parameters to nyt and they can do the validations
        if (!empty($author)) {
            $params['author'] = $author;
        }

        if (!empty($isbn)) {
            // We are going to loop through each isbn
            $isbns = explode(';', $isbn);
            $list_isbns = '';
            foreach ($isbns as $new_isbn) {
                $size = strlen($new_isbn);
                // if the isbn is not the right length send an error message
                if ($size !== 10 && $size !== 13) {
                    return response()->json([
                        'status' => '401',
                        'message' => 'ISBN must be either 10 or 13 digits'
                    ], 401);
                } else {
                    // otherwise add it to the list
                    $list_isbns = $new_isbn . ';';
                }
            }

            // Remove the last character from the list (;) and add it to the parameters
            $params['isbn'] = substr($list_isbns, 0, -1);
        }

        if (!empty($title)) {
            $params['title'] = $title;
        }

        if (!empty($offset)) {
            // check offset is divisible by 20 or is 0
            if ($offset % 20 !== 0) {
                return response()->json([
                    'status' => '401',
                    'message' => 'offset must be multiple of 20'
                ], 401);
            } elseif ($offset < 0) {
                return response()->json([
                    'status' => '401',
                    'message' => 'offset must be greater than or equal to 0'
                ], 401);
            } else {
                $params['offset'] = $offset;
            }
        }

        // Get the response from NYT
        $response = Http::get($url, $params);

        return $response->body();
    }
}
