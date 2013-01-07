<?php
namespace PHPlayer\MusicBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PHPlayer\MusicBundle\Helper\FileHelper;

class PackageCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('phplayer:package')
            ->setDescription('Remove some files and folders as to prepare a zipped distribution version')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln('Packaging.');

        $root = realpath(dirname(__FILE__).'/../../../..');
        $output->writeln($root);

        if (basename($root) == 'PHPlayer') {
            $output->writeln('Not operating on a copy of PHPlayer, stopping.');
            return;
        }

        $output->writeln('Removing .git dirs');
        $this->rmGit($root);
        $output->writeln('');

        // Clear some temp dirs
        foreach (array('app/cache', 'app/logs', 'web/uploads') as $dir) {
            foreach ( array_diff(scandir($dir), array('.','..')) as $file) {
                if (is_file("$root/$dir/$file")) {
                    unlink("$root/$dir/$file");
                } else {
                    $output->writeln("removing ". "$root/$dir/$file");
                }
            }
        }
    }


    protected function rmGit($dir) {
        $output = $this->output;

        foreach ( array_diff(scandir($dir), array('.','..')) as $file) {
            if (is_dir($dir.'/'.$file)) {
                if ($file == '.git') {
                    $output->writeln("removing: \t".$dir.'/'.$file);
                    FileHelper::delTree($dir.'/'.$file);
                } else {
                    $this->rmGit($dir.'/'.$file);
                }
            }
        }
    }
}