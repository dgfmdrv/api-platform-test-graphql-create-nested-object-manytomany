<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Service\GraphqlClient;
use App\Exception\GraphQLQueryException;

class GraphQlUpdateManyToOneCommand extends Command
{
    use LockableTrait;

    private LoggerInterface $logger;
    private ManagerRegistry $dManReg;
    private GraphqlClient $clientBackend;
    private ParameterBagInterface $params;


	public function __construct(
        LoggerInterface $logger, 
        ManagerRegistry $dManReg, 
        GraphqlClient $clientBackend,
        ParameterBagInterface $params
        )
    {
        parent::__construct();
        $this->logger = $logger;
        $this->dManReg = $dManReg;
        $this->clientBackend = $clientBackend;
        $this->params = $params;
	}
	
	protected function configure()
	{
		$this->setName('app:prueba');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{	
    // 
    $str="Obteniendo maquinas";
    $output->writeln($str);  $this->logger->info($str);
    
    // read maquinas
    $query = <<<'GRAPHQL'
      query {
        maquinas {
         id
        }
      }
    GRAPHQL;
    
    $resultado=$this->clientBackend->query($query);    
    $output->writeln("query");
    print_r($resultado);
    
    // create activo
    $mutation = <<<'GRAPHQL'
      mutation{
        createActivo (input: {
          nombre:"PRUEBA7",
          referencia:"PRUEBA7",
          maquina: [
            "/index.php/api/maquinas/1",
            "/index.php/api/maquinas/2"
          ],
          inversion: [
            {
              fechaAdquisicion: "2021-11-16T13:33:00Z",
              coste: 1000,
              ubicacion:"ub",
              perfil:"per"      
            }
          ]
        }) {
          activo {
            id
            maquina {
              id
            }
            inversion {
              id
            }
          }
        }
      }
    GRAPHQL;


    $resultado=$this->clientBackend->query($mutation, []);
    
    // 
    $output->writeln("mutation");
    print_r($resultado);    
    
    // if not released explicitly, Symfony releases the lock
    // automatically when the execution of the command ends
    $this->release();

    return 0;
  }
}
