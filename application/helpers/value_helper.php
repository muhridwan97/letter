<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('numerical')) {
    /**
     * Helper get decimal value if needed.
     * @param $number
     * @param int $precision
     * @param bool $trimmed
     * @param string $dec_point
     * @param string $thousands_sep
     * @return int|string
     */
    function numerical($number, $precision = 3, $trimmed = true, $dec_point = ',', $thousands_sep = '.')
    {
        if (empty($number)) {
            return 0;
        }
        $formatted = number_format($number, $precision, $dec_point, $thousands_sep);

        if (!$trimmed) {
            return $formatted;
        }

        // Trim unnecessary zero after comma: (2,000 -> 2) or (3,200 -> 3,2)
        return strpos($formatted, $dec_point) !== false ? rtrim(rtrim($formatted, '0'), $dec_point) : $formatted;;

        /* Trim only zero after comma: (2,000 -> 2) but (3,200 -> 3,200)
        $decimalString = '';
        for ($i = 0; $i < $precision; $i++) {
            $decimalString .= '0';
        }
        $trimmedNumber = str_replace($dec_point . $decimalString, "", (string)$formatted);
        return $trimmedNumber;
        */
    }
}

if (!function_exists('if_empty')) {
    /**
     * Helper get decimal value if needed.
     * @param $value
     * @param string $default
     * @param string $prefix
     * @param string $suffix
     * @param bool $strict
     * @return array|string
     */
    function if_empty($value, $default = '', $prefix = '', $suffix = '', $strict = false)
    {
        if (is_null($value) || empty($value)) {
            return $default;
        }

        if ($strict) {
            if ($value == '0' || $value == '-' || $value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
                return $default;
            }
        }

        if (is_array($value)) {
            return $value;
        }

        return is_null($default) ? $value : $prefix . $value . $suffix;
    }
}


if (!function_exists('get_if_exist')) {
    /**
     * Helper get decimal value if needed.
     * @param $array
     * @param string $key
     * @param string $default
     * @return array|string
     */
    function get_if_exist($array, $key = '', $default = '')
    {
        if (is_array($array) && key_exists($key, if_empty($array, []))) {
            if (!empty($array[$key])) {
                return $array[$key];
            }
        }

        return $default;
    }
}

if (!function_exists('format_date')) {
    /**
     * Helper get date with formatted value.
     * @param $value
     * @param string $format
     * @return string
     */
    function format_date($value, $format = 'Y-m-d')
    {
        if (empty($value) || $value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
            return '';
        }
        $dateParts = explode('/', $value);
        if (count($dateParts) == 3) {
            $value = $dateParts['1'] . '/' . $dateParts['0'] . '/' . $dateParts['2'];
        }
        try {
            return (new DateTime($value))->format($format);
        } catch (Exception $e) {
            return '';
        }
    }
}

if (!function_exists('relative_time')) {

    /**
     * Convert string to relative time format.
     *
     * @param $ts
     * @return false|string
     */
    function relative_time($ts)
    {
        if (!ctype_digit($ts)) {
            $ts = strtotime($ts);
        }
        $diff = time() - $ts;
        if ($diff == 0) {
            return 'now';
        } elseif ($diff > 0) {
            $day_diff = floor($diff / 86400);
            if ($day_diff == 0) {
                if ($diff < 60) return 'just now';
                if ($diff < 120) return '1 minute ago';
                if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if ($diff < 7200) return '1 hour ago';
                if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if ($day_diff == 1) {
                return 'Yesterday';
            }
            if ($day_diff < 7) {
                return $day_diff . ' days ago';
            }
            if ($day_diff < 31) {
                return ceil($day_diff / 7) . ' weeks ago';
            }
            if ($day_diff < 60) {
                return 'last month';
            }
            return date('F Y', $ts);
        } else {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if ($day_diff == 0) {
                if ($diff < 120) {
                    return 'in a minute';
                }
                if ($diff < 3600) {
                    return 'in ' . floor($diff / 60) . ' minutes';
                }
                if ($diff < 7200) {
                    return 'in an hour';
                }
                if ($diff < 86400) {
                    return 'in ' . floor($diff / 3600) . ' hours';
                }
            }
            if ($day_diff == 1) {
                return 'Tomorrow';
            }
            if ($day_diff < 4) {
                return date('l', $ts);
            }
            if ($day_diff < 7 + (7 - date('w'))) {
                return 'next week';
            }
            if (ceil($day_diff / 7) < 4) {
                return 'in ' . ceil($day_diff / 7) . ' weeks';
            }
            if (date('n', $ts) == date('n') + 1) {
                return 'next month';
            }
            return date('F Y', $ts);
        }
    }
}

if (!function_exists('difference_date')) {
    /**
     * Helper get difference by two dates.
     * @param $firstDate
     * @param $secondDate
     * @param string $format
     * @return string
     */
    function difference_date($firstDate, $secondDate, $format = '%R%a')
    {
        $date1 = date_create($firstDate);
        $date2 = date_create($secondDate);
        $diff = date_diff($date1, $date2);
        $diffInFormat = $diff->format($format);

        return intval($diffInFormat);
    }
}

if (!function_exists('print_debug')) {
    /**
     * Print pre formatted data.
     * @param $data
     * @param bool $die_immediately
     */
    function print_debug($data, $die_immediately = true)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($die_immediately) {
            die();
        }
    }
}

if (!function_exists('time_elapsed_string')) {
	/**
	 * Get humanize format.
	 *
	 * @param $datetime
	 * @param false $full
	 * @return string
	 * @throws Exception
	 */
	function time_elapsed_string($datetime, $full = false)
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
}

if (!function_exists('extract_number')) {
    /**
     * Extract number from value.
     * @param $value
     * @return null|string|string[]
     */
    function extract_number($value)
    {
        $value = preg_replace("/[^0-9-,\/]/", "", $value);
        $value = preg_replace("/,/", ".", $value);
        return $value;
    }
}

if (!function_exists('roman_number')) {
    /**
     * Generate number to roman value.
     * @param $integer
     * @param bool $upcase
     * @return string
     */
    function roman_number($integer, $upcase = true)
    {
        $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }
        return $upcase ? $return : strtolower($return);
    }
}

if (!function_exists('get_week_date_range')) {
	/**
	 * Standard ISO-8601 week 1 start from sunday,
	 * if first week contain 4 days then week 1 start from there, instead start from next week.
	 *
	 * @param $week
	 * @param $year
	 * @return mixed
	 */
	function get_week_date_range($week, $year) {
		$dto = new DateTime();
		$ret['week_start'] = $dto->setISODate($year, $week, 0)->format('Y-m-d');
		$ret['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
		return $ret;
	}
}

if (!function_exists('get_week_date_range_sql_mode_2')) {
	/**
	 * Week 1 always continue the rest of last week from last year.
	 *
	 * @param $week
	 * @param $year
	 * @return mixed
	 */
	function get_week_date_range_sql_mode_2($week, $year) {
		$firstDateYear = \Carbon\Carbon::createFromDate($year, 1, 1);
		$adjuster = 0;
		if ($firstDateYear->weekday() <= 3) {
			$adjuster = 1;
		}

		$dto = new DateTime();
		$ret['week_start'] = $dto->setISODate($year, $week + $adjuster, 0)->format('Y-m-d');
		$ret['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
		return $ret;
	}
}

if (!function_exists('get_week_date_range_sql_mode_0')) {
	/**
	 * Get week range with sql compatible mode 0
	 *
	 * @param $week
	 * @param $year
	 * @return array|string
	 */
	function get_week_date_range_sql_mode_0($week, $year)
	{
		$weeks = [];
		$date = \Carbon\Carbon::now();

		$adjuster = 0;
		$firstDateYear = \Carbon\Carbon::createFromDate($year, 1, 1);
		if ($firstDateYear->weekday() >= 5) {
			$adjuster = 1;
		}

		for ($i = 1; $i <= 53; $i++) {
			$date->setISODate($year, $i - $adjuster);
			if ($i == 1) {
				$weekStart = $year . '-01-01';
			} else {
				$weekStart = $date->startOfWeek(0)->toDateString();
			}
			if ($i <= 52) {
				$weekEnd = $date->endOfWeek(6)->toDateString();
			} else {
				$weekEnd = $year . '-12-31';
			}
			$weeks[$i] = [
				'week_start' => $weekStart,
				'week_end' => $weekEnd,
			];
		}

		if (!is_null($week)) {
			return get_if_exist($weeks, $week);
		}

		return $weeks;
	}
}

if (!function_exists('get_months')) {
	function get_months($index = null)
	{
		$months = array(
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July ',
			'August',
			'September',
			'October',
			'November',
			'December',
		);
		if (!empty($index)) {
			return $months[$index];
		}
		return $months;
	}
}

if (!function_exists('detect_chat_id')) {
	/**
	 * Normalize chat id.
	 *
	 * @param $chatId
	 * @return string
	 */
	function detect_chat_id($chatId)
	{
		$chatId = str_replace([' ', '+'], '', $chatId);
		if (strpos($chatId, '-') !== false) {
			if (!(strpos($chatId, '@g.us') !== false)) {
				$chatId .= '@g.us';
			}
		} else if (!(strpos($chatId, '@c.us') !== false)) {
            $chatId = preg_replace('/^08/', '628', $chatId);
			$chatId .= '@c.us';
		}

		return $chatId;
	}
}
