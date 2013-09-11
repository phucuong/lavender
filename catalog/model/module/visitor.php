<?php
/*
Visitor Module code by T2vn
07 04 2012
http://www.opencart.vn
*/
class ModelModuleVisitor extends Model
{
	/**
	 * get data from a flat file
	 * @param string	$name	module name
	 * @return array
	 */
	public function getFileData( $name ) {
		$expire		= $this->config->get( $name . '_expire' );
		$filename	= DIR_LOGS . 'counter.txt';
		$ret		= array();

		// do some checks
		if( !$expire ) {
			$expire = '600';
		}

		if( !file_exists( $filename ) ) {
			fopen( $filename, 'wb' );
			chmod( $filename, 0644 );
		}

		$ignore			= false;
		$current_agent	= ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? addslashes( trim( $_SERVER['HTTP_USER_AGENT'] ) ) : 'no agent';
		$current_time	= time();
		$current_ip		= $_SERVER['REMOTE_ADDR'];

		// read file
		$fileData		= array();
		$handle			= fopen( $filename, 'rb' );

		if( $handle ) {
			while( !feof( $handle ) ) {
				$line = trim( fgets( $handle, 4096 ) );
				if( $line != '' ) {
					$fileData[] = $line;
				}
			}

			fclose( $handle );
		}else{
			$ignore = true;
		}

		// get amount of entries
		$count = count( $fileData );

		// ignore bots
		if( substr_count( $current_agent, 'bot' ) > 0 ) {
			$ignore = true;
		}

		// if this ip is already recorded during the last session (see $expire) ignore
		for( $i = 1; $i < $count; ++$i ) {
			list( $counter_ip, $counter_time ) = explode( '#', $fileData[$i] );
			$counter_time = trim( $counter_time );

			if( $counter_ip == $current_ip && $current_time - $expire < $counter_time ) {
				// visitor already counted, break
				$ignore = true;
				break;
			}
		}

		// increase counter
		if( $ignore == false ) {
			if( $count == 0 ) {
				// counter is empty, fill with data
				$data = date( 'z' ) . ':1#' . date( 'W') . ':1#' . date( 'n') . ':1#' . date( 'Y') . ':1#1#1#' . $current_time . "\n"
				. $current_ip . '#' . $current_time . "\n"
				;

				// write data
				$fp = fopen( $filename, 'wb+' );
				if( $fp ) {
					flock( $fp, LOCK_EX );
					fwrite( $fp, $data );
					flock( $fp, LOCK_UN );
					fclose( $fp );
				}

				// contruct data
				$day			= $week = $month = $year = $all = $record = 1;
				$record_time	= $current_time;
				$online		= 1;
			}else{
				// increase counter
				list( $day_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time ) = explode( '#', $fileData[0] );

				// day
				$day = $this->_getValue( $day_arr, 1 );
				if( $this->_getValue( $day_arr, 0 ) == date( 'z' ) ) {
					++$day;
				}else{
					$day = 1;
				}

				// week
				$week = $this->_getValue( $week_arr, 1 );
				if( $this->_getValue( $week_arr, 0  ) == date( 'W' ) ) {
					++$week;
				}else{
					$week = 1;
				}

				// month
				$month = $this->_getValue( $month_arr,1  );
				if( $this->_getValue( $month_arr, 0  ) == date( 'n') ) {
					++$month;
				}else{
					$month = 1;
				}

				// year
				$year = $this->_getValue( $year_arr, 1 );
				if( $this->_getValue( $year_arr, 0 ) == date( 'Y' ) ) {
					++$year;
				}else{
					$year = 1;
				}

				// all
				++$all;

				// new record
				$record_time = trim( $record_time );
				if( $day > $record ) {
					$record			= $day;
					$record_time	= $current_time;
				}

				// write data and check how many online
				$online = 1;
				chmod( $filename, 0755 );
				$fp = fopen( $filename, 'wb+' );

				if( $fp ) {
					flock( $fp, LOCK_EX );
					$data = date( 'z' ) .':'
					. $day .'#'. date( 'W' ) .':'
					. $week .'#' . date( 'n' ) .':'
					. $month .'#' . date( 'Y' ) .':'
					. $year .'#'. $all . '#'
					. $record . '#'
					. $record_time . "\n"
					;
					fwrite( $fp, $data );

					for( $i = 1; $i < $count; ++$i ) {
						list( $counter_ip, $counter_time ) = explode( '#', $fileData[$i] );

						// save if time is newer
						if( $current_time - $expire < $counter_time ) {
							$counter_time	= trim( $counter_time );
							$data			= $counter_ip . '#' . $counter_time . "\n";
							fwrite( $fp, $data );
							++$online;
						}
					}

					$data = $current_ip . '#' . $current_time . "\n";
					fwrite( $fp, $data );
					flock( $fp, LOCK_UN );
					fclose( $fp );
					chmod( $filename, 0644 );
				}
			}

			$ret['day']		= $day;
			$ret['week']	= number_format( $week, 0, '.', '.' );
			$ret['month']	= number_format( $month, 0, '.', '.' );
			$ret['year']	= number_format( $year, 0, '.', '.' );
			$ret['online']	= $online;
			$ret['all']		= number_format( $all, 0, '.', '.' );
		}else{
			// read data for output
			if( $count > 0 ) {
			   	list( $day_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time) = explode( '#', $fileData[0] );
			}else{
				list( $day_arr, $week_arr, $month_arr, $year_arr, $all, $record, $record_time ) = explode( '#', date( 'z') .':1#'. date( 'W' ) .':1#'. date( 'n') .':1#' . date( 'Y' ) .':1#1#1#'. $current_time );
			}

			$ret['day']		= $this->_getValue( $day_arr );
			$ret['week']	= number_format( $this->_getValue( $week_arr ), 0, '.', '.' );
			$ret['month']	= number_format( $this->_getValue( $month_arr ), 0, '.', '.' );
			$ret['year']	= number_format( $this->_getValue( $year_arr ), 0, '.', '.' );
			$ret['online']	= $count - 1;
			$ret['all']		= number_format( $all, 0, '.', '.' );
		}

		return $ret;
	}

	/**
	 * split value by seperator
	 * @param array	$arr
	 * @param int	field value to return
	 * @return string
	 */
	private function _getValue( $arr, $field = 1 ) {
		$data	= explode( ':', $arr );
		return $data[$field];
	}
}
?>