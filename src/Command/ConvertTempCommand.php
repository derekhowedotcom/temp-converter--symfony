<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

final class ConvertTempCommand extends Command
{
    
    protected $temperatureSclae;
    protected $temperatureUnit;
    protected $convertedUnit;

    
     // setup command, description, and parameters
    protected function configure()
    {
        $this->setName('temperature-converter');
        $this->setDescription('Converts temperatures from Fahrenheit to Celsius and from Celsius to Fahrenheit.');
        // $this->addArgument('temperatureUnit', InputArgument::REQUIRED, 'temperature to be converted.');
        // $this->addArgument('temperatureSclae', InputArgument::REQUIRED, 'temperature to be converted.');
        $this->addArgument('temperatureSclae', InputArgument::OPTIONAL, 'Provide a temperature Scale');
        $this->addArgument('temperatureUnit', InputArgument::OPTIONAL, 'Provide a temperature Unit');
    }

    //getters/setters
    public function setTemperatureSclae($x) {
        $this->temperatureSclae = $x;
    }
    public function getTemperatureSclae() {
        return $this->temperatureSclae;
    }

    public function setTemperatureUnit($x) {
        $this->temperatureUnit = $x;
    }
    public function getTemperatureUnit() {
        return $this->temperatureUnit;
    }

    public function setConvertedUnit($x) {
        $this->convertedUnit = $x;
    }
    public function getConvertedUnit() {
        return $this->convertedUnit;
    }

    protected function clacFahrenheit($value) 
    {
        return ((9/5) * $value) + (32);
    }
    protected function clacCelsius($value) 
    {
        return ($value - 32) * (5/9);
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');
        $question = new Question('Please choose if you wish to convert from celsius to fahrenheit(F), or fahrenheit to celsius(C) (F/C): ', 'F');
        $temperatureSclae = $helper->ask($input, $output, $question);
        //make sure we are using uppercase
        $this->setTemperatureSclae(strtoupper($temperatureSclae));

        $helper = $this->getHelper('question');
        $question2 = new Question('Please enter the unit of temperature to convert: ', '20');
        $temperatureUnit = $helper->ask($input, $output, $question2);
        $this->setTemperatureUnit(strtoupper($temperatureUnit));

       //Better user validation would go here.
       //check the scale and output so the user knows
       // $output->writeln('Scale: '.$temperatureSclae);
        if ($this->getTemperatureSclae() === 'F') {
            $output->writeln('Scale to convert to: Celsius to Fahrenheit');
        }else if($this->getTemperatureSclae() === 'C'){
            $output->writeln('Scale to convert to: Fahrenheit to Celsius');
        }else{
            $this->setTemperatureSclae('F');
            $output->writeln('No or invalid Scale selected. We assume: Celsius to Fahrenheit');
        }

        //check the unit entered is a number and if anything else was entered assume room temperature   
        //Better user validation would go here.  
        if(is_numeric($this->getTemperatureUnit())){
            $output->writeln('Unit to convert: '.$this->getTemperatureUnit());
        }else{
            if($this->getTemperatureSclae() === 'F'){
                $this->setTemperatureUnit(20);
            }else{
                $this->setTemperatureUnit(68);
            }
            $output->writeln('No or invalid Unit entered to convert. We assume room temperature: '.$this->getTemperatureUnit());
        }

        if($this->getTemperatureSclae() === 'F'){
            $this->setConvertedUnit($this->clacFahrenheit($this->getTemperatureUnit()));
        }else{
            $this->setConvertedUnit($this->clacCelsius($this->getTemperatureUnit()));
        }        

        //Output result with 2 decimal places
        $output->writeln(sprintf('Conversion Result: %s', number_format((float)$this->getConvertedUnit(), 2, '.', '')));

        return self::SUCCESS;
    }
}

?>