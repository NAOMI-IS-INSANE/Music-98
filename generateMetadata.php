<?php
header("content-type: application/json");
// Include getID3 library
require_once('getid3/getid3.php');

function &ensureNestedArray(&$array, $keys) {
    foreach ($keys as $key) {
        if (!isset($array[$key])) {
            $array[$key] = array();
        }
        $array = &$array[$key];
    }
    return $array;
}
function findFlacFiles($dir) {
    $flacFiles = [];
    // Get all files and directories inside $dir
    $items = glob(rtrim($dir, '/') . '/*');
    // Loop through each item
    foreach ($items as $item) {
        // If the item is a directory, recursively search for flac files
        if (is_dir($item)) {
            $flacFiles = array_merge($flacFiles, findFlacFiles($item));
        } 
        // If the item is a file and has a .flac extension, add it to the list
        elseif (is_file($item) && pathinfo($item, PATHINFO_EXTENSION) === 'flac') {
            $flacFiles[] = $item;
        }
    }
    return $flacFiles;
}

function generateMetadata($musicFolderPath) {
    // Get list of FLAC files in the Music folder
    $flacFiles = findFlacFiles($musicFolderPath);
    // Initialize getID3
    $getID3 = new getID3();
    // Create Audio Tag Arrays
    $stats = array();
    $stats["tracks"] = 0;
    $artists = array();
    // Loop Through each audio file
    foreach ($flacFiles as $flacFile) {
        // Open file in getID3 to pull info
        $fileInfo = $getID3 -> analyze($flacFile);
        // Create fileDetails to store REQUIRED METADATA
        $stats["tracks"] += 1;
        $fileDetails = array();
        $fileDetails["title"] = isset($fileInfo['tags']['vorbiscomment']["title"][0]) ? $fileInfo['tags']['vorbiscomment']["title"][0] : "";
        $fileDetails["album"] = isset($fileInfo['tags']['vorbiscomment']["album"][0]) ? $fileInfo['tags']['vorbiscomment']["album"][0] : $fileDetails["title"];
        $fileDetails["artist"] = isset($fileInfo["tags"]['vorbiscomment']["artist"]) ? $fileInfo["tags"]['vorbiscomment']["artist"] : "UNKNOWN ARTIST";
        $fileDetails["albumartist"] = isset($fileInfo['tags']['vorbiscomment']["albumartist"][0]) ? $fileInfo['tags']['vorbiscomment']["albumartist"][0] : $fileDetails["artist"][0];
        $fileDetails["date"] = isset($fileInfo['tags']['vorbiscomment']["date"][0]) ? $fileInfo['tags']['vorbiscomment']["date"][0] : "0000";
        $fileDetails["releasedate"] = isset($fileInfo['tags']['vorbiscomment']["releasedate"][0]) ? $fileInfo['tags']['vorbiscomment']["releasedate"][0] : $fileDetails["date"]."-00-00";
        $fileDetails["media"] = isset($fileInfo['tags']['vorbiscomment']["sourcemedia"][0]) ? $fileInfo['tags']['vorbiscomment']["sourcemedia"][0] : "UNKNOWN MEDIA";
        $fileDetails["copynumber"] = isset($fileInfo['tags']['vorbiscomment']["copynumber"][0]) ? $fileInfo['tags']['vorbiscomment']["copynumber"][0] : "UNKNOWN COPY";
        $fileDetails["discnumber"] = isset($fileInfo['tags']['vorbiscomment']["discnumber"][0]) ? $fileInfo['tags']['vorbiscomment']["discnumber"][0] : "UNKNOWN DISC";
        $fileDetails["tracknumber"] = isset($fileInfo['tags']['vorbiscomment']["tracknumber"][0]) ? $fileInfo['tags']['vorbiscomment']["tracknumber"][0] : "UNKNOWN TRACK";
        $fileDetails['file'] = array();
        $fileDetails['file']['base'] = dirname($flacFile);
        $fileDetails['file']['location'] = basename($flacFile);
        $keys = [
            $fileDetails["albumartist"],
            $fileDetails["releasedate"],
            $fileDetails["album"],
            $fileDetails["media"],
            $fileDetails["copynumber"],
            $fileDetails["discnumber"],
            $fileDetails["tracknumber"]
        ];
        $nestedArray = &ensureNestedArray($artists, $keys);
        $nestedArray = array(
            "title"=>$fileDetails["title"],
            "artist"=>$fileDetails["artist"],
            "file"=>$flacFile
        );
        if (!isset($stats["artistTracks"][$fileDetails["albumartist"]])) {
            $stats["artistTracks"][$fileDetails["albumartist"]] = 1;
        } else {
            $stats["artistTracks"][$fileDetails["albumartist"]] += 1;
        }
        if (!isset($stats["mediaTracks"][$fileDetails["media"]])) {
            $stats["mediaTracks"][$fileDetails["media"]] = 1;
        } else {
            $stats["mediaTracks"][$fileDetails["media"]] += 1;
        }
        if (!isset($stats["dateTracks"][$fileDetails["date"]])) {
            $stats["dateTracks"][$fileDetails["date"]] = 1;
        } else {
            $stats["dateTracks"][$fileDetails["date"]] += 1;
        }
        if (!isset($stats["copyTracks"][$fileDetails["copynumber"]])) {
            $stats["copyTracks"][$fileDetails["copynumber"]] = 1;
        } else {
            $stats["copyTracks"][$fileDetails["copynumber"]] += 1;
        }
    };
    // Save Tracks to json also
    arsort($stats["artistTracks"]);
    arsort($stats["mediaTracks"]);
    krsort($stats["dateTracks"]);
    $json = json_encode($stats);
    file_put_contents('Stats_'.str_replace('/', '-', $musicFolderPath).'.json', $json);
    ksort($artists);
    foreach ($artists as $artist => $date) {
        krsort($artists[$artist]);
    }
    $json = json_encode($artists);
    file_put_contents('Artists_'.str_replace('/', '-', $musicFolderPath).'.json', $json);
    // Return track
    return $artists;
}

echo json_encode(generateMetadata("Music"));