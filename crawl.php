<?php
/*
 * Wefeelfiner, a crawler of wefeelfine.org/gallery
 * Copyright (C) 2009-2010
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('config.php');

// get the last saved ID and increment it
$id = intval(file_get_contents('id.txt'));
$id++;

// read the new montage page
$contents = file_get_contents('http://www.wefeelfine.org/gallery/montage.php?id=' . $id);

// match the image link
$matches = array();
preg_match("/'..\/data\/images\/(.*)\.jpg'/", $contents, $matches);

// check if we matched something
if (empty($matches) || empty($matches[1]) || strlen($matches[1]) <= 0)
	die('No new image found!');

// build the new image url
$image = 'http://www.wefeelfine.org/data/images/' . $matches[1] . '.jpg';

// output debug info
echo("Found this image: <a href='$image' target='_blank'>$image</a><br/>");

// fix slashes in output dir
$settings['outputdir'] = str_ireplace('\\', '/', $settings['outputdir']);
if (substr($settings['outputdir'], -1) != '/') $settings['outputdir'] .= '/';

// download the image and store it
$imgfile = $settings['outputdir'] . $id . '.jpg';
file_put_contents($imgfile, file_get_contents($image));

// output debug info
echo("<img src='$imgfile'/>");

// save the new ID to file
file_put_contents('id.txt', $id);