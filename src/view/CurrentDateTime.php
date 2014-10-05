<?php
  namespace view;

  class CurrentDateTime {
    private $months = array('Januari', 'Februari', 'Mars', 'April',
      'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober',
      'November', 'December');
    private $days = array('Måndag', 'Tisdag', 'Onsdag',
      'Torsdag', 'Fredag', 'Lördag', 'Söndag');

    /**
      * Generates the current date and time. Formattes it
      * in swedish "Lördag, den 13 September år 2014. Klockan är [16:40:13].".
      *
      * @return string
      */
    public function getCurrentDateTime() {
      $date = new \DateTime();

      return $this->days[$date->format('N')-1] . ", den " .
        $date->format('j') . " " . $this->months[$date->format('n')-1] .
        " år " . $date->format('Y') . ". Klockan är " . "[" .
        $date->format('H:i:s') . "].";;
    }
  }
