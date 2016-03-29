<?php namespace App\Ecofy\Util;

/**
 * Represents a set of records with header.
 */
class CsvUtil {

	/**
	 * Returns an array of records represented as associative array
	 * The first line must be a heaer which maps to column
	 * @param string $csvStrData the text where each line is a csv.
	 */
	public static function toAssociativeArray($csvStrData)
	{
		$lines = explode("\n", $csvStrData);

		$headers = array();
		$result = array();
		$isFirst = true;
		foreach($lines as $line) {
			$line = trim($line);
			if (empty($line))
				continue;
			$row = str_getcsv($line);

			if ($isFirst) {
				$isFirst = false;
				$headers = $row;
			} else {
				$tuple = array();
				for($i = 0; $i < count($headers); $i++ ) {
					 $tuple[$headers[$i]] = $row[$i];
				}
				$result[] = $tuple;
			}
		}

		return $result;
	}
}
