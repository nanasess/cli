<?php

/*
 * This file is part of EC-CUBE2 CLI.
 *
 * (C) Tsuyoshi Tsurushima.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube2\Command\Cache;

use Eccube2\Init;
use Eccube2\Util\MasterData;
use Eccube2\Util\Parameter;
use Eccube2\Util\Template;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{
    protected static $defaultName = 'cache:clear';

    /** @var MasterData */
    protected $masterData;

    /** @var Parameter */
    protected $parameter;

    /** @var Template */
    protected $template;

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        Init::init();

        $this->masterData = new MasterData();
        $this->parameter = new Parameter();
        $this->template = new Template();
    }

    protected function configure()
    {
        $this
            ->setName(static::$defaultName)
            ->setDescription('キャッシュクリア')
            ->addOption('no-warmup', null, InputOption::VALUE_NONE, 'パラメータキャッシュを再生成しない')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $noWarmup = $input->getOption('no-warmup');

        $this->masterData->clearAllCache();
        $output->writeln('    <info>マスタキャッシュクリア</info>');

        if ($noWarmup) {
            $this->parameter->clearCache();
            $output->writeln('    <info>パラメーターキャッシュクリア</info>');
        } else {
            $this->parameter->createCache();
            $output->writeln('    <info>パラメーターキャッシュクリア/生成</info>');
        }

        $this->template->clearAllCache();
        $output->writeln('    <info>テンプレートキャッシュクリア</info>');

        $output->writeln('    <info>キャッシュをクリアしました。</info>');
    }
}
