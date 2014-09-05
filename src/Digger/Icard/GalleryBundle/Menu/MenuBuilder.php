<?php

namespace Digger\Icard\GalleryBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(Request $request, SecurityContext $securityContext)
    {	
        $menu = $this->factory->createItem('root', array('attributes' => array('class' => 'nav')));
        $menu->setChildrenAttributes(array('class' => 'nav'));
        $menu->setCurrent($request->getRequestUri());
        
        $menu->addChild('Index', array('route' => 'home'));
        $menu->addChild('Upload', array(
            'route' => 'upload',
          ///  'attributes' => array('class' => 'btn btn-success')
        ));
        // ... add more children

        return $menu;
	}
}
