<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait FileUpload
{

  /**
   * The function creates a filename for a given file by appending the current date and time, the
   * original filename without the extension, and the lowercase extension.
   */
  public function createFilename($file)
  {
    $name = $file->getClientOriginalName();
    $filename = Carbon::now()->format('dmY_His') . '_' . trim(pathinfo($name, PATHINFO_FILENAME)) . '.' . Str::lower(pathinfo($name, PATHINFO_EXTENSION));
    return $filename;
  }

  /**
   * The function creates an image by moving the original file to a specified directory and returns the new filename.
   */
  public function createImage($file, $filename, $id, $type)
  {
    // Move the original file to the post's directory
    $filename = $file->move('users/' . $id . '/' . $type . '/', $filename);
    return $filename;
  }

  /**
   * The function creates a thumbnail of an image and saves it in a specified directory.
   */
  public function createThumbnail($file, $filename, $id, $type)
  {
    // Create thumbnail of image
    $thumbnail = Image::make($file)->resize(300, 185, function ($constraint) {
      $constraint->aspectRatio();
    });

    $thumbnailDirectory = 'users/' . $id . '/' . $type;

    if (!file_exists($thumbnailDirectory)) {
      mkdir($thumbnailDirectory, 0777, true);
    }

    // You may also use the standard PHP method to save the image
    $thumbnail->save('users/' . $id . '/' . $type . '/' . $filename);
  }

  /**
   * The function checks if a file exists at a given URL and if so, deletes it along with its
   * corresponding thumbnail.
   */
  public function unlink($url, $thumbnailUrl)
  {
    // Delete file if exist
    if (File::exists($url) && $url != "") {
      unlink($thumbnailUrl);
      unlink($url);
    }
  }

  /**
   * The function deletes a directory and its contents if it exists.
   */
  public function deleteDirectory($url)
  {
    // Check file if exist
    if (File::exists($url) && $url != "") {
      // Delete the folder and its contents
      File::deleteDirectory($url);
    }
  }
}
