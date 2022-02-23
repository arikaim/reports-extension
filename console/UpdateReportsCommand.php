<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
 */
namespace Arikaim\Extensions\Reports\Console;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Output\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Arikaim\Extensions\Reports\Classes\ReportUpdate;
use Arikaim\Core\Console\ConsoleCommand;
use Arikaim\Core\Db\Model;

/**
 * Update reports command
 */
class UpdateReportsCommand extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('reports:update')->setDescription('Udate all reports.');        
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function executeCommand($input, $output)
    {       
        $this->showTitle();

        $reports = Model::Reports('reports')->activeQuery()->get();
       
        $helper = $this->getHelper('question');
        $question = new Question("\t Enter Year: ",null);    
        $year = $helper->ask($input, $output, $question);
        $year = (empty($year) == true) ? null : $year;

        $question = new Question("\t Enter Month: ",null);    
        $month = $helper->ask($input, $output, $question);
        $month = (empty($month) == true) ? null : $month;

        $question = new Question("\t Enter Day: ",null);    
        $day = $helper->ask($input, $output, $question);
        $day = (empty($day) == true) ? null : $day;

        $errors = 0;
        foreach ($reports as $report) {
            $result = ReportUpdate::updateReport($report,$day,$month,$year);
            $errors += ($result == false) ? 1 : 0;              
        }

        if ($errors == 0) {
            $this->showCompleted();
        } else {
            $this->showError('Error');
        }
    }
}
