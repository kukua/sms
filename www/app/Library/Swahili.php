<?php

namespace App\Library;

class Swahili {

	public static function day($day) {
		switch(strtolower($day)) {
			case 'sunday':
				return 'jumapili';
				break;
			case 'monday':
				return 'jumatatu';
				break;
			case 'tuesday':
				return 'jumanne';
				break;
			case 'wednesday':
				return 'jumatano';
				break;
			case 'thursday':
				return 'alhamisi';
				break;
			case 'friday':
				return 'iljumaa';
				break;
			case 'saturday':
				return 'jumamosi';
				break;
			default:
				return '';
				break;
		}
	}

	public static function dayTemp($temp) {
		if ($temp >= 20) {
			return "Joto nyakati za mchana $temp";
		}
		if ($temp < 20) {
			return "Baridi nyakati za mchana $temp";
		}
		return '';
	}

	public static function nightTemp($temp) {
		if ($temp >= 20) {
			return "Joto nyakati za usiku $temp";
		}
		if ($temp < 20) {
			return "Baridi nyakati za usiku $temp";
		}
		return '';
	}

	public static function rainChance($value) {
		if ($value <= 10) {
			return "Hakuna mvua";
		} elseif ($value > 10 && $value <= 50) {
			return "Uwezekano mdogo wa mvua kunyesha";
		} elseif ($value > 50 && $value <= 70) {
			return "Kuna uwezekano wa mvua kunyesha";
		} elseif ($value > 70) {
			return "Mvua itanyesha";
		}
	}

	public static function wind($wind) {
		if ($wind <= 5) {
			return "Upepo ni wa kawaida";
		}
		if ($wind > 5) {
			return "Upepo mkali";
		}
		return '';
	}
}
