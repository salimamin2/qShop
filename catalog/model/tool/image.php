<?php

class ModelToolImage extends Model {

    function resize($filename, $width, $height) {
        if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $old_image = $filename;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $ext;

        if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!file_exists(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }
            include_once(DIR_SYSTEM . 'library/wideimage/WideImage.php');
            //Registry::getInstance()->load->library('wideimage/WideImage');
            $image = WideImage::load(DIR_IMAGE . $old_image);
            //d(array($width,$height));
            $image->resize($width, $height)->saveToFile(DIR_IMAGE . $new_image);
            /* $image = new Image(DIR_IMAGE . $old_image);
              $image->resize($width, $height);
              $image->save(DIR_IMAGE . $new_image); */
        }

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            return HTTPS_IMAGE . $new_image;
        } else {
            return HTTP_IMAGE . $new_image;
        }
    }

}

?>