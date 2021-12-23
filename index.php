<?php require './functions.php';
$error = ""; ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <link rel="icon"
        href="https://asset.kompas.com/crops/QKH0VYnPJGmv_D6RLuQCvrqZ7FA=/126x57:573x355/750x500/data/photo/2020/10/08/5f7eaaaf6526c.png"
        type="image/x-icon">
    <meta property="og:image"
        content="https://asset.kompas.com/crops/QKH0VYnPJGmv_D6RLuQCvrqZ7FA=/126x57:573x355/750x500/data/photo/2020/10/08/5f7eaaaf6526c.png">
    <meta property="og:author" content="Xnuvers007">
    <meta property="og:title" content="Music and Video Youtube Downloader">
    <meta name="description" content="Download Music / video from youtube">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Download YouTube video</title>
    <!-- Font-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: black;
    }

    .formSmall {
        width: 700px;
        margin: 20px auto 20px auto;
    }
    </style>

</head>

<body color="black">
    <div class="container">
        <form method="post" action="" class="formSmall">
            <div class="row">
                <div class="col-lg-12">
                    <h7 class="text-align"> Download YouTube Video</h7>
                </div>
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" class="form-control" name="video_link"
                            placeholder="Paste link.. e.g. https://www.youtube.com/watch?v=OK_JCtrrv-c"
                            <?php if (isset($_POST['video_link'])) echo "value='" . $_POST['video_link'] . "'"; ?>>
                        <span class="input-group-btn">
                            <button type="submit" name="submit" id="submit" class="btn btn-primary">Go!</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div><!-- .row -->
        </form>

        <?php if ($error) : ?>
        <div style="color:red;font-weight: bold;text-align: center"><?php print $error ?></div>
        <?php endif; ?>

        <?php if (isset($_POST['submit'])) : ?>


        <?php
            $video_link = $_POST['video_link'];
            // parse_str(parse_url($video_link, PHP_URL_QUERY), $parse_url);
            $video_id =  getYoutubeId($video_link);
            $video = json_decode(getVideoInfo($video_id));
            $formats = $video->streamingData->formats;
            $adaptiveFormats = $video->streamingData->adaptiveFormats;
            $thumbnails = $video->videoDetails->thumbnail->thumbnails;
            $title = $video->videoDetails->title;
            $short_description = $video->videoDetails->shortDescription;
            $thumbnail = end($thumbnails)->url;
            ?>


        <div class="row formSmall">
            <div class="col-lg-3">
                <img src="<?php echo $thumbnail; ?>" style="max-width:100%">
            </div>
            <div class="col-lg-9">
                <h2><?php echo $title; ?> </h2>
                <p><?php echo str_split($short_description, 100)[0]; ?></p>
            </div>
        </div>

        <?php if (!empty($formats)) : ?>
        <?php if (@$formats[0]->url == "") : ?>
        <div class="card formSmall">
            <div class="card-header">
                <strong>This video is currently not supported by our downloader!</strong>
                <small><?php
                                    $signature = "https://example.com?" . $formats[0]->signatureCipher;
                                    parse_str(parse_url($signature, PHP_URL_QUERY), $parse_signature);
                                    $url = $parse_signature['url'] . "&sig=" . $parse_signature['s'];
                                    ?>
                </small>
            </div>
        </div>
        <?php
                    die();
                endif;
                ?>

        <div class="card formSmall">
            <div class="card-header">
                <strong>With Video & Sound</strong>
            </div>

            <div class="card-body">
                <table class="table ">
                    <tr>
                        <td>URL</td>
                        <td>Type</td>
                        <td>Quality</td>
                        <td>Download</td>
                    </tr>
                    <?php foreach ($formats as $format) : ?>
                    <?php

                                if (@$format->url == "") {
                                    $signature = "https://example.com?" . $format->signatureCipher;
                                    parse_str(parse_url($signature, PHP_URL_QUERY), $parse_signature);
                                    $url = $parse_signature['url'] . "&sig=" . $parse_signature['s'];
                                    //var_dump($parse_signature);
                                } else {
                                    $url = $format->url;
                                }




                                ?>
                    <tr>
                        <td><a href="<?php echo $url; ?>">Test</a></td>
                        <td>
                            <?php if ($format->mimeType) echo explode(";", explode("/", $format->mimeType)[1])[0];
                                        else echo "Unknown"; ?>
                        </td>
                        <td>
                            <?php if ($format->qualityLabel) echo $format->qualityLabel;
                                        else echo "Unknown"; ?>
                        </td>
                        <td>
                            <a
                                href="downloader.php?link=<?php echo urlencode($url) ?>&title=<?php echo urlencode($title) ?>&type=<?php if ($format->mimeType) echo explode(";", explode("/", $format->mimeType)[1])[0];
                                                                                                                                                else echo "mp4"; ?>">
                                Download
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>


        <div class="card formSmall">
            <div class="card-header">
                <strong>Videos video only/ Audios audio only</strong>
            </div>
            <div class="card-body">
                <table class="table ">
                    <tr>
                        <td>Type</td>
                        <td>Quality</td>
                        <td>Download</td>
                    </tr>
                    <?php foreach ($adaptiveFormats as $video) : ?>
                    <?php
                                try {
                                    $url = $video->url;
                                } catch (Exception $e) {
                                    $signature = $video->signatureCipher;
                                    parse_str(parse_url($signature, PHP_URL_QUERY), $parse_signature);
                                    $url = $parse_signature['url'];
                                }

                                ?>
                    <tr>
                        <td><?php if (@$video->mimeType) echo explode(";", explode("/", $video->mimeType)[1])[0];
                                        else echo "Unknown"; ?></td>
                        <td><?php if (@$video->qualityLabel) echo $video->qualityLabel;
                                        else echo "Unknown"; ?></td>
                        <td><a
                                href="downloader.php?link=<?php print urlencode($url) ?>&title=<?php print urlencode($title) ?>&type=<?php if ($video->mimeType) echo explode(";", explode("/", $video->mimeType)[1])[0];
                                                                                                                                                else echo "mp4"; ?>">Download</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>


        <?php endif; ?>

    </div>
    <marquee style="color: green">Create with ❤️️ by<a href="https://github.com/Xnuvers007"> Indra / Xnuvers00</a> thank support from <a href="https://github.com/ihsandevs"> Ihsan Devs </a></marquee>
</body>

</html>
