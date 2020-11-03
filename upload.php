<?php 

if(!empty($_FILES['photos']['name'][0])) {
    $photos = $_FILES['photos'];
    $uploaded = array();
    $failed =array();
    $allowed = array('jpg', 'png', 'gif');

    foreach($photos['name'] as $position => $photo_name) {
        $photo_tmp = $photos['tmp_name'][$position];
        $photo_size = $photos['size'][$position];
        $photo_error = $photos['error'][$position];
        $photo_ext = explode('.', $photo_name);
        $photo_ext = strtolower(end($photo_ext));
       
        if(in_array($photo_ext, $allowed)) {

            if($photo_error === 0) {

                if($photo_size <= 1048576) {

                    $photo_name_new = uniqid("", true) . '.' .$photo_ext;
                    $photo_destination = 'upload/' . $photo_name_new; 

                        if(move_uploaded_file($photo_tmp, $photo_destination)) {
                            $uploaded[$position] = $photo_destination;
                        } else {
                            $failed[$position] = "[$photo_name] failed to upload";
                        }

                } else  {
                    $failed[$position] = "[$photo_name] is too large.";
                }

            } else {
                $failed[$position] = "[$photo_name] errored with code {$photo_error}";
            }

        } else {
            $failed[$position] = "[{$photo_name}] file extension '{[$photo_ext}]' is not allowed !";
        }
    };

    // if (!empty($uploaded)) {
    //     print_r($uploaded);
    // }
    
    // if(!empty($failed)) {
    //     print_r($failed);
    // }
} 

$photos = new FilesystemIterator(__DIR__.'/upload', FilesystemIterator::SKIP_DOTS);
// var_dump($photos); die;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'upload de fichiers</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <h2>Upload File</h2>
        <label for="fileUpload">Fichier:</label>
        <input type="file" name="photos[]" multiple>
        <input type="submit" name="submit" value="Upload">
        <p></p><strong>Note:</strong> Only .jpg, .jpeg, .jpeg, .gif, .png are authorized until 1 Mo.</p>
    </form>

    <div>
        <?php 

            foreach($photos as $photo) { 
                // var_dump($photo); die;

                ?>
                <img src='upload/<?= $photo->getFileName();?>' alt="">
                <figcaption><?= $photo->getFilename()?></figcaption>
           <?php } ?>

    </div>

</body>
</html>