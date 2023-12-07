<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait FileUpload
{

  public function createFilename($file)
  {
    $name = $file->getClientOriginalName();
    $filename = Carbon::now()->format('dmY_His') . '_' . trim(pathinfo($name, PATHINFO_FILENAME)) . '.' . Str::lower(pathinfo($name, PATHINFO_EXTENSION));
    return $filename;
  }
  public function createImage($file, $filename, $id, $type)
  {
    // Move the original file to the post's directory
    $filename = $file->move('users/' . $id . '/' . $type . '/', $filename);
    return $filename;
  }

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

  public function unlink($url, $thumbnailUrl)
  {
    // Delete file if exist
    if (File::exists($url) && $url != "") {
      unlink($thumbnailUrl);
      unlink($url);
    }
  }
}
