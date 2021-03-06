<?php
namespace PwCommentsTeam\PwComments\Utility;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2011-2015 Armin Ruediger Vieweg <armin@v.ieweg.de>
 *  |     2015 Dennis Roemmich <dennis@roemmich.eu>
 */

/**
 * Cookie Utility
 *
 * @package PwCommentsTeam\PwComments
 */
class Cookie {
	/** Cookie Prefix */
	const COOKIE_PREFIX = 'tx_pwcomments_';
	/** Lifetime of cookie in days */
	const COOKIE_LIFETIME_DAYS = 365;

	/**
	 * Get cookie value
	 *
	 * @param string $key
	 * @return string|NULL
	 */
	static public function get($key) {
		if (isset($_COOKIE[self::COOKIE_PREFIX . $key])) {
			return $_COOKIE[self::COOKIE_PREFIX . $key];
		}
		return NULL;
	}

	/**
	 * Set cookie value
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	static public function set($key, $value) {
		$cookieExpireDate = time() + self::COOKIE_LIFETIME_DAYS * 24 * 60 * 60;
		setcookie(
			self::COOKIE_PREFIX . $key,
			$value,
			$cookieExpireDate,
			'/',
			self::getCookieDomain(),
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieSecure'] > 0,
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieHttpOnly'] == 1
		);
	}

	/**
	 * Gets the domain to be used on setting cookies. The information is
	 * taken from the value in $GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieDomain']
	 *
	 * @return string The domain to be used on setting cookies
	 */
	static protected function getCookieDomain() {
		$result = '';
		$cookieDomain = $GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieDomain'];
		if (!empty($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['cookieDomain'])) {
			$cookieDomain = $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['cookieDomain'];
		}
		if ($cookieDomain) {
			if ($cookieDomain[0] == '/') {
				$match = array();
				$matchCnt = @preg_match($cookieDomain, \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'), $match);
				if ($matchCnt !== FALSE) {
					$result = $match[0];
				}
			} else {
				$result = $cookieDomain;
			}
		}
		return $result;
	}
}