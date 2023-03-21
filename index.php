<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getPartsFromFullname ($fullname) {
    $a = ['surname', 'name', 'patronymic'];
    $array = explode(' ', $fullname);
	return	array_combine($a, $array);
};

function getFullnameFromParts ($surname, $name, $patronymic) {
	return $surname.' '.$name.' '.$patronymic;
};

function getShortName ($fullname) {
	$a = getPartsFromFullname($fullname);
	return $a['name'].' '.mb_substr($a['patronymic'], 0, 1).'.';
};

function getGenderFromName ($fullname) {
	// внутри функции делим ФИО на составляющие с помощью функции getPartsFromFullname;
		$a = getPartsFromFullname($fullname);
		$gender = 0;

		if (mb_substr($a['patronymic'], -3) == 'вна')
			$gender--;
		if (mb_substr($a['name'], -1) == 'а')
			$gender--;
		if (mb_substr($a['surname'], -2) == 'ва')
			$gender--;

		if (mb_substr($a['patronymic'], -2) == 'ич')
			$gender++;
		if (mb_substr($a['name'], -1) == 'й' or mb_substr($a['name'], -1) == 'н')
			$gender++;
		if (mb_substr($a['surname'], -1) == 'в')
			$gender++;

		if ($gender > 0) {
			return 1;
		} elseif ($gender < 0) {
			return -1; 
		} else {
			return 0;
		}
};

function getGenderDescription ($array) {

  $gen = count($array);

	$male = count(array_filter($array, function($k) {
    return getGenderFromName($k['fullname']) == 1;
  }));
    
  $female = count(array_filter($array, function($k) {
    return getGenderFromName($k['fullname']) == -1;
  }));
        
  $undef = count(array_filter($array, function($k) {
    return getGenderFromName($k['fullname']) == 0;
  }));

  $malePercent = round($male/$gen*100, 2);
  $femalePercent = round($female/$gen*100, 2);
  $undefPercent = round($undef/$gen*100, 2);

	return <<<TEXT
	Гендерный состав аудитории:
	---------------------------
	Мужчины - $malePercent%
	Женщины - $femalePercent%
	Не удалось определить - $undefPercent%
TEXT;
};

function getPerfectPartner ($surname, $name, $patronymic, $array) {
    $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronymic = mb_convert_case($patronymic, MB_CASE_TITLE_SIMPLE);
    $fullname = getFullnameFromParts($surname, $name, $patronymic);
    $genderFirst = getGenderFromName($fullname);
    $randomPerson = $array[array_rand($array)]['fullname'];
    $genderSecond = getGenderFromName($randomPerson);
    if ($genderFirst == -$genderSecond and $genderFirst != 0) {
    	$shortFirst = getShortName($fullname);
    	$shortSecond = getShortName($randomPerson);
    	$randNum = round(rand(5000, 10000)/100, 2);
        return <<<TEXT
    $shortFirst + $shortSecond = 
    ♡ Идеально на $randNum% ♡
TEXT;
    } else {
    	return getPerfectPartner ($surname, $name, $patronymic, $array);
    };
};