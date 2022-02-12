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

     // setup command, description, and parameters
    protected function configure()
    {
        $this->setName('temperature-converter');
        $this->setDescription('Converts temperatures from Fahrenheit to Celsius and from Celsius to Fahrenheit.');
        // $this->addArgument('temperatureUnit', InputArgument::REQUIRED, 'temperature to be converted.');
        // $this->addArgument('temperatureSclae', InputArgument::REQUIRED, 'temperature to be converted.');
        $this->addArgument('temperatureSclae', InputArgument::OPTIONAL, 'Provide a temperature Scale');
        $this->addArgument('temperatureUnit', InputArgument::OPTIONAL, 'Provide a temperature Scale');
        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');
        $question = new Question('Please choose if you wish to convert from celsius to fahrenheit(F), or fahrenheit to celsius(C) (F/C): ', 'F');
        $temperatureSclae = $helper->ask($input, $output, $question);
        //make sure we are using uppercase
        $temperatureSclae = strtoupper($temperatureSclae);

        $helper = $this->getHelper('question');
        $question2 = new Question('Please enter the unit of temperature to convert: ', '20');
        $temperatureUnit = $helper->ask($input, $output, $question2);
        $temperatureUnit = strtoupper($temperatureUnit);

       // $output->writeln('Scale: '.$temperatureSclae);
       //check the scale and output so the user knows
        if ($temperatureSclae === 'F') {
            $output->writeln('Scale to convert to: Celsius to Fahrenheit');
        }else if($temperatureSclae === 'C'){
            $output->writeln('Scale to convert to: Fahrenheit to Celsius');
        }else{
            $temperatureSclae = 'F';
            $output->writeln('No or invalid Scale selected. We assume: Celsius to Fahrenheit');
        }

        //check the unit entered is a number and if anything else was entered assume room temperature     
        if(is_numeric($temperatureUnit)){
            $output->writeln('Unit to convert: '.$temperatureUnit);
        }else{
            if($temperatureSclae === 'F'){
                $temperatureUnit = 20;
            }else{
                $temperatureUnit = 68;
            }
            $output->writeln('No or invalid Unit entered to convert. We assume room temperature: '.$temperatureUnit);
        }

        if($temperatureSclae === 'F'){
            $convertedUnit = ((9/5) * $temperatureUnit) + (32);
        }else{
            $convertedUnit = ($temperatureUnit - 32) * (5/9);
        }        

        $output->writeln(sprintf('Conversion Result: %s', $convertedUnit));


        // $temperatureUnit = $input->getArgument('temperatureUnit');

        // //celsius to fahrenheit    
        // $convertedUnit = ((9/5) * $temperatureUnit) + (32);

        // //fahrenheit to celsius     
        // // $convertedUnit = ($value - 32) * (5/9);


        // $output->writeln(sprintf(
        //     $temperatureUnit . ' celsius converted to fahrenheit is: %s', $convertedUnit
        // ));

        // return value is important when using CI, to fail the build when the command fails
        // in case of fail: "return self::FAILURE;"
        return self::SUCCESS;
    }
}

?>