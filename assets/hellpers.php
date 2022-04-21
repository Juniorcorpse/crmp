<?php
define('URL_TEST', 'https://www.localhost/crmp');
define('URL_BASE', 'https://joinpartners.dev.br/_/testes/crm_teste');
/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

 /**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
	$string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
	$formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
	$replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

	$slug = str_replace(["-----", "----", "---", "--"], "-",
		str_replace(" ", "-",
			trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
		)
	);
	return $slug;
}

function str_studly_case(string $string): string
{
	$string = str_slug($string);
	$studlyCase = str_replace(" ", "",
		mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
	);

	return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
	return lcfirst(str_studly_case($string));
}

 
/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
	$string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
	$arrWords = explode(" ", $string);
	$numWords = count($arrWords);

	if ($numWords < $limit) {
		return $string;
	}

	$words = implode(" ", array_slice($arrWords, 0, $limit));
	return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
	$string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
	if (mb_strlen($string) <= $limit) {
		return $string;
	}

	$chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
	return "{$chars}{$pointer}";
}

/**
 *
 * @param string|null $price
 * @return string
 */
function str_price(?string $price): string
{
	return number_format((!empty($price) ? $price : 0), 2, ",", ".");
}


/**
 *
 * @param string $text
 * @return string
 */
function str_textarea(string $text): string
{
	$text = filter_var($text, FILTER_SANITIZE_STRIPPED);
	return "<p>" . preg_replace('#\R+#', '</p><p>', $text) . "</p>";
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * formato rever de uma data date_fmt_br
 * @param string|null $date
 * @return string|null
 */
function date_fmt_back(?string $date): ?string
{
	if (!$date) {
		return null;
	}
	if (strpos($date, " ")) {
		$date = explode(" ", $date);
		return implode("-", array_reverse(explode("/", $date[0]))) . " " . $date[1];
	}

	return implode("-", array_reverse(explode("/", $date)));
}

/**
     * Formata a data que sera gravada no banco
     * @param string|null $param
     * @return string|null
     * @throws Exception
     */
    function convertDateToDB(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

 /**
 * @param string $path
 * @return string
 */
function url(string $path = null): string
{
	if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
		if ($path) {
			return URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
		}
		return URL_TEST;
	}

	if ($path) {
		return URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
	}

	return URL_BASE;
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
	header("HTTP/1.1 302 Redirect");
	if (filter_var($url, FILTER_VALIDATE_URL)) {
		header("Location: {$url}");
		exit;
	}

}


function dateMongo($date){
	return (new MongoDB\BSON\UTCDateTime($date))
	->toDateTime()
	->setTimeZone(new \DateTimeZone('America/Belem'))->format(DATE_ATOM);
}

function mongoDate( $timestamp = '' ) {

    if ( empty($timestamp) && $timestamp != 0 ) {
        return false;
    }


    if ( strlen( (string) $timestamp ) == 13 ) { // timestamp length in MilliSeconds
        return new MongoDB\BSON\UTCDateTime( (int) $timestamp );
    }

    return new MongoDB\BSON\UTCDateTime( (int) $timestamp * 1000 );

}

