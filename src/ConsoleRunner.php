<?php declare(strict_types=1);

namespace twisted1919\console;

use yii\base\Component;
use yii\base\InvalidConfigException;
use twisted1919\helpers\Common as CommonHelper;

/**
 * ConsoleRunner - a component for running console commands in background.
 *
 * Usage:
 * ```
 * ...
 * $cr = new ConsoleRunner([
 *     'file' => '@my/path/to/yii',
 *     'phpBinaryPath' => '/my/path/to/php', // This is an optional param you may use to override the default `php` binary path.
 * ]);
 * $cr->run('controller/action param1 param2 ...');
 * ...
 * ```
 * or use it like an application component:
 * ```
 * // config.php
 * ...
 * components [
 *     'consoleRunner' => [
 *         'class' => twisted1919\console\ConsoleRunner::class,
 *         'file' => '@my/path/to/yii', // Or an absolute path to console file.
 *         'phpBinaryPath' => '/my/path/to/php', // This is an optional param you may use to override the default `php` binary path.
 *     ]
 * ]
 * ...
 *
 * // some-file.php
 * Yii::$app->consoleRunner->run('controller/action param1 param2 ...');
 * ```
 */
class ConsoleRunner extends Component
{
    /**
     * Usually it can be `yii` file.
     *
     * @var string Console application file that will be executed.
     */
    public $file;

    /**
     * @var string The PHP binary path.
     */
    public $phpBinaryPath = PHP_BINDIR . '/php';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->file === null) {
            throw new InvalidConfigException('The "file" property must be set.');
        } else {
            $this->file = get_alias($this->file);
        }
    }

	/**
	 * Running console command
	 * 
	 * @param string $cmd
	 * @param bool $sendBackground
	 *
	 * @return bool
	 */
    public function run(string $cmd, bool $sendBackground = true): bool
    {
	    if (!CommonHelper::functionExists('popen') || !CommonHelper::functionExists('pclose')) {
		    return false;
	    }
	    
        $cmd = $this->phpBinaryPath . ' -q ' . $this->file . ' ' . $cmd;
	    
	    if (!$this->isWindows()) {
	    	$cmd .= ' >/dev/null 2>&1' . ($sendBackground ? ' &' : '');
	    } else {
	    	$cmd = 'start' . ($sendBackground ? ' /b' : '') . ' ' . $cmd; 
	    }
	    
	    if (($res = popen($cmd, 'r')) === false) {
		    return false;
	    }

	    return (int)pclose($res) === 0;
    }

    /**
     * Check operating system.
     *
     * @return boolean `true` if it's Windows OS.
     */
    protected function isWindows(): bool
    {
        return stripos(PHP_OS, 'win') === 0;
    }
}
