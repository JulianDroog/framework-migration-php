<?php
declare(strict_types=1);

namespace App\Commands;

use Cake\Error\FatalErrorException;
use LaravelZero\Framework\Commands\Command;

class FileRenameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migration:file-rename {path : The path of the application you want to migrate (required)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $from = 'cakephp';

    public $to = 'laravel';

    protected string $path = '';

    protected bool $git = false;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->path = $this->arguments()['path'] . '/';

        foreach (config('framework')[$this->from] as $folder => $folderPath) {
            if (is_dir($this->path . $folderPath)) {
                $destination = config('framework')[$this->to][$folder];
                // dd($destination);
                $firstPart = explode('/', $destination)[0];
                // $paths = str_split($destination, '/');
                if (!is_dir($this->path . $firstPart)) {
                    mkdir($this->path . $firstPart, 0777);
                }
                $this->rename(
                    $this->path . $folderPath,
                    $this->path . $destination
                );
//            $this->renameSubFolders($this->path . 'templates');
//            $this->changeExt($this->path . 'templates');
            }
        }

//        foreach ((array)Configure::read('App.paths.plugins') as $path) {
//            $this->io->out("Renaming templates in <info>{$path}</info>");
//            $iterator = new DirectoryIterator($path);
//            foreach ($iterator as $dirInfo) {
//                $dirPath = $dirInfo->getRealPath();
//                if ($dirInfo->isDot() || !is_dir($dirPath . '/src/Template')) {
//                    continue;
//                }
//
//                $this->rename(
//                    $dirPath . '/src/Template',
//                    $dirPath . '/templates'
//                );
//                $this->renameSubFolders($dirPath . '/templates');
//                $this->changeExt($dirPath . '/templates');
//            }
//        }

        $this->info('Operation executed');
    }

    /**
     * Rename file or directory
     *
     * @param string $source Source path.
     * @param string $dest Destination path.
     * @return void
     * @throws \Cake\Error\FatalErrorException When we're unable to move the folder via git.
     */
    protected function rename(string $source, string $dest): void
    {
        $this->info("Move $source to $dest");
//        if ($this->args->getOption('dry-run')) {
//            return;
//        }

        $parent = dirname($dest);
        if (!is_dir($parent)) {
            $old = umask(0);
            mkdir($parent, 0755, true);
            umask($old);
        }

        if ($this->git) {
            $restore = getcwd();
            chdir($this->path);
            $gitOutput = [];
            $returnVar = null;
            $lastLine = exec("git mv $source $dest", $gitOutput, $returnVar);
            if ($returnVar) {
                $this->error(sprintf(
                    'Unable to move: %s to : %s - Reason: %s - Hint: Maybe you have uncommited changes in git.',
                    $source,
                    $dest,
                    $lastLine
                ));
            }

            chdir($restore);
        } else {
            rename($source, $dest);
        }
    }

    /**
     * Rename Layout, Element, Cell, Plugin to layout, element, cell, plugin
     * respectively.
     *
     * @param string $path Path.
     * @return void
     */
    protected function renameSubFolders(string $path): void
    {
//        $this->io->out("Moving sub directories of <info>$path</info>");
//        if ($this->args->getOption('dry-run')) {
//            return;
//        }
//
//        $folders = ['Layout', 'Element', 'Cell', 'Email', 'Plugin', 'Flash'];
//
//        foreach ($folders as $folder) {
//            $dirIter = new RecursiveDirectoryIterator(
//                $path,
//                RecursiveDirectoryIterator::UNIX_PATHS
//            );
//            $iterIter = new RecursiveIteratorIterator($dirIter);
//            $templateDirs = new RegexIterator(
//                $iterIter,
//                '#/' . $folder . '/\.$#',
//                RecursiveRegexIterator::SPLIT
//            );
//
//            foreach ($templateDirs as $val) {
//                $this->renameWithCasing(
//                    $val[0] . '/' . $folder,
//                    $val[0] . '/' . strtolower($folder)
//                );
//            }
//        }
    }

    /**
     * Recursively change template extension to .php
     *
     * @param string $path Path
     * @return void
     */
    protected function changeExt(string $path): void
    {
//        $this->io->out("Recursively changing extensions in <info>$path</info>");
//        if ($this->args->getOption('dry-run')) {
//            return;
//        }
//        $dirIter = new RecursiveDirectoryIterator(
//            $path,
//            RecursiveDirectoryIterator::SKIP_DOTS | RecursiveDirectoryIterator::UNIX_PATHS
//        );
//        $iterIter = new RecursiveIteratorIterator($dirIter);
//        $templates = new RegexIterator(
//            $iterIter,
//            '/\.ctp$/i',
//            RecursiveRegexIterator::REPLACE
//        );
//
//        foreach ($templates as $val) {
//            $this->rename($val . '.ctp', $val . '.php');
//        }
    }

    /**
     * Rename file or directory with case hacks for git.
     *
     * @param string $source Source path.
     * @param string $dest Destination path.
     * @return void
     */
    protected function renameWithCasing(string $source, string $dest): void
    {
        $this->io->verbose("Move $source to $dest with filesystem casing");
        if ($this->args->getOption('dry-run')) {
            return;
        }

        $parent = dirname($dest);
        if (!is_dir($parent)) {
            $old = umask(0);
            mkdir($parent, 0755, true);
            umask($old);
        }
        $tempDest = $dest . '_';

        if ($this->git) {
            $restore = getcwd();
            chdir($this->path);
            $gitOutput = [];
            $returnVar = null;
            $lastLine = exec("git mv $source $tempDest", $gitOutput, $returnVar);
            if ($returnVar) {
//                throw new RuntimeException(sprintf(
//                    'Unable to move: %s to : %s - Reason: %s - Hint: Maybe you have uncommited changes in git.',
//                    $source,
//                    $tempDest,
//                    $lastLine
//                ));
            }
            $gitOutput = [];
            $returnVar = null;
            $lastLine = exec("git mv $tempDest $dest", $gitOutput, $returnVar);
            if ($returnVar) {
//                throw new RuntimeException(sprintf(
//                    'Unable to move: %s to : %s - Reason: %s - Hint: Maybe you have uncommited changes in git.',
//                    $tempDest,
//                    $dest,
//                    $lastLine
//                ));
            }
            chdir($restore);
        } else {
            rename($source, $tempDest);
            rename($tempDest, $dest);
        }
    }
}
