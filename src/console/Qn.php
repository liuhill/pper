<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2019/7/12
 * Time: 4:47 PM
 * 查找本地文件上传到七牛云
 */

namespace Console;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Models\Message;
use Models\Qiniu;

/**
 * Class Qn
 * @package Console
 *
 *执行命令:  php cli.php app:qn
 */
class Qn extends Base
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:qn')


            // the short description shown while running "php bin/console list"
            ->setDescription('Refresh latest post on Algolia dataset.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you refresh Algolia dataset with latest post.')

            /** 参数

            ->addArgument('name', InputArgument::REQUIRED, 'Who do you want to greet?')
            ->addArgument('last_name', InputArgument::OPTIONAL, 'Your last name?')
            //*/
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if( $this->qiniu['enable'] ) {
            $tbObj = new Message($this->db);
            $rows = $tbObj->getLocal();
            foreach($rows as $row) {

                $name = basename($row['resource']);
                $uid = $row['uid'];
                $qn = new Qiniu($this->qiniu);
                $url = $qn->upload($row['src'],"pper_uid$uid"."_".$name);
                if( $url !== false ) {
                    $tbObj->updateUrl($url,$uid);
                }
            }

            // Example code
            $output->writeLn("上传成功");
        }

        else {
            $output->writeLn("上传七牛云设置为false");
        }
    }
}