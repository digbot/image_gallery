<?php

namespace Digger\Icard\GalleryBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;  
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use RuntimeException;
use InvalidArgumentException;
use Digger\Icard\GalleryBundle\Entity\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FetchImagesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        parent::configure();
        $this->setName('gellery:fetch')
             ->setDescription('Update Bulscore Player info.')
        ;  
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
         $url = 'http://en.wikipedia.org/wiki/List_of_snack_foods';
         $this->em = $this->getContainer()->get('doctrine')->getManager();
         $hmltPage = $this->fetchPage($url);
         $crawler  = new Crawler();
         $crawler->addContent($hmltPage);
         $filter = $crawler->filter('table.wikitable tr')
            ->reduce(function (Crawler $node, $i) {
            // filte title
            return ($i <= 0) == 0;
         });
       
         if (iterator_count($filter) > 1) {
            // iterate over filter results
            foreach ($filter as $i => $content) {
                // create crawler instance for result
                $crawler = new Crawler($content);
                $data = $crawler->filter('td')->each(function (Crawler $node, $i) {
                    return $node->text();
                });
                
                $file = NULL;
                try {
                  $file = $crawler->filter('td img')->first()->attr('srcset');
                } catch(InvalidArgumentException $e) {
                    $file = NULL;
                }
                if ($file) {
                    $this->createImage($data, $file);
                }
            }
        } else {
               throw new RuntimeException('Got empty result processing the dataset!');
        }
   }
   
   private function createImage($data, $urlFile)
   {
       $title = $data[0];
       $note  = $data[3]."\n\r\n\r County: " . $data[2];
       $fileData = file_get_contents('http:'.$urlFile);
       file_put_contents('temp_file_disk', $fileData);
       
       $i = new Image();
       $i->setTitle($title);
       $i->setNote($note);
       $i->setFile(new UploadedFile('temp_file_disk', 'Image1', null, null, null, true));
       $this->em->persist($i);
       $this->em->flush();
       
       echo "Inset item with id: ".$i->getId() ." title: ".$i->getTitle();
       echo "\n\r";
   }
   
   private function fetchPage($url)
   {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);   

        return $output;
    }
}


