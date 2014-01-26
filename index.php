<?php
/**
 * This is the main web entry point for the Walking Tour Displayer.
 *
 * If you are able to read this in a web browser, the server this is
 * running on is *not* set up correctly to work with PHP applications.
 * See php.net for installation instructions.
 *
 * --------
 * By using this software, you are advised that:
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
 * REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
 * INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
 * LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
 * OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
 * PERFORMANCE OF THIS SOFTWARE.
 *
 * @file
 */

# This is a valid entry point
define( 'ENTRYPOINT', true );

# store install directory in a variable for convenience
$WD = realpath( '.' );
if ( !$WD ) { # realpath returns FALSE on failure
    $WD = dirname( __DIR__ );
}

# include default settings
require_once( "$WD/includes/defaultSettings.php" );

# try to get manual settings
if ( file_exists ( "$WD/config.php" ) ) {
  require_once "$WD/config.php";
} else {
  # if file doesn't exist, assume we require installation
  header( 'Location: install.php' );
  die( 'Installation required!' );
}

require_once 'includes/database.php';
$result = executeSql( 'SELECT * from `tbl_routes`' );
if ( $result === FALSE ) {
  # something went wrong
  die( 'Unable to fetch walks from database.' );
}
$rows = "";
while ( $walk = $result->fetch_array() ) {
  # TODO: this is horrible, needs fixing
  $rows .= '<tr><td>';
  $rows .= '<a href="walks.php?walk=';
  $rows .= $walk['id'];
  $rows .= '">';
  $rows .= $walk['title'];
  $rows .= '</a></td><td>';
  $rows .= $walk['shortDesc'];
  $rows .= '</td></tr>';
}
require( 'includes/templates.php' );
render( 'homepage', $rows );
