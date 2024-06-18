<?php
$stats = json_decode(file_get_contents("Stats_Music.json"), true);
$totalTracks = isset($stats["tracks"]) ? $stats["tracks"] : "?";
?>
<form>
    <select name="artists">
        <optgroup label="Artists">
            <option value="">All Artists (<?php echo($totalTracks); ?>)</option>
            <?php foreach ($stats["artistTracks"] as $artist => $track) {
                echo "<option value=\"\">".$artist." (".$track.")</option>";
            } ?>
        </optgroup>
    </select>
    <select name="year">
        <optgroup label="Years">
            <option value="">All Years (<?php echo($totalTracks); ?>)</option>
            <?php foreach ($stats["dateTracks"] as $artist => $track) {
                echo "<option value=\"\">".$artist." (".$track.")</option>";
            } ?>
        </optgroup>
    </select>
    <select name="media">
        <optgroup label="Media Types">
            <option value="">All Media Types (<?php echo($totalTracks); ?>)</option>
            <?php foreach ($stats["mediaTracks"] as $artist => $track) {
                echo "<option value=\"\">".$artist." (".$track.")</option>";
            } ?>
        </optgroup>
    </select>
</form>
<fieldset>
    <legend>Artists</legend>
    <div class="sunken-panel"></div>
</fieldset>