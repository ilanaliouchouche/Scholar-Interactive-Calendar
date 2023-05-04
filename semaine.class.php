<?php
class Semaine
{
	// class properties
	private $storage = "data\calendrier.json";
	private $date_lundi;
	private $date_mardi;
	private $date_mercredi;
	private $date_jeudi;
	private $date_vendredi;
	private $lundi = array();
	private $mardi = array();
	private $mercredi = array();
	private $jeudi = array();
	private $vendredi = array();

	// class methods
	public function __construct($date_lundi)
	{
		$date_mardi = date('Y-m-d', strtotime('+1 day', strtotime($date_lundi)));
		$date_mercredi = date('Y-m-d', strtotime('+2 day', strtotime($date_lundi)));
		$date_jeudi = date('Y-m-d', strtotime('+3 day', strtotime($date_lundi)));
		$date_vendredi = date('Y-m-d', strtotime('+4 day', strtotime($date_lundi)));


		$calendrier = json_decode(file_get_contents($this->storage), true);

		foreach ($calendrier as $creneau) {
			if ($creneau['date'] == $date_lundi) {
				array_push($this->lundi, $creneau);
			} elseif ($creneau['date'] == $date_mardi) {
				array_push($this->mardi, $creneau);
			} elseif ($creneau['date'] == $date_mercredi) {
				array_push($this->mercredi, $creneau);
			} elseif ($creneau['date'] == $date_jeudi) {
				array_push($this->jeudi, $creneau);
			} elseif ($creneau['date'] == $date_vendredi) {
				array_push($this->vendredi, $creneau);
			}
		}
		$this->date_lundi = date_format(date_create($date_lundi), "d/m/Y");
		$this->date_mardi = date_format(date_create($date_mardi), "d/m/Y");
		$this->date_mercredi = date_format(date_create($date_mercredi), "d/m/Y");
		$this->date_jeudi = date_format(date_create($date_jeudi), "d/m/Y");
		$this->date_vendredi = date_format(date_create($date_vendredi), "d/m/Y");
	}

	public function getLundi()
	{
		return $this->lundi;
	}

	public function getMardi()
	{
		return $this->mardi;
	}

	public function getMercredi()
	{
		return $this->mercredi;
	}


	public function getJeudi()
	{
		return $this->jeudi;
	}

	public function getVendredi()
	{
		return $this->vendredi;
	}

	public function getDateLundi()
	{
		return $this->date_lundi;
	}

	public function getDateMardi()
	{
		return $this->date_mardi;
	}

	public function getDateMercredi()
	{
		return $this->date_mercredi;
	}

	public function getDateJeudi()
	{
		return $this->date_jeudi;
	}

	public function getDateVendredi()
	{
		return $this->date_vendredi;
	}

	public function setDateLundi($date) {
		$this->date_lundi = $date;
	}

	public function getNextOrPreviousWeek($date, $next = true) {
		$timestamp = strtotime($date);
		$dayOfWeek = date('N', $timestamp);
		$delta = $next ? 7 : -7;
		$newTimestamp = strtotime("$delta days", $timestamp);
		return date('m/d/Y', strtotime("next monday", $newTimestamp - ($dayOfWeek - 1) * 86400));
	}

	public function getDateByDayOfWeek($dayOfWeek) {
    $date = DateTime::createFromFormat('d/m/Y', $this->getDateLundi());
    $date->modify("+$dayOfWeek days");
    return $date->format('Y-m-d');
}

	
}