<?php
namespace Magiccart\Shopfranchise\Plugin;

use Magento\Framework\Data\Tree\Node;


class Topmenu
{
    /**
     * @var \Magiccart\Shopfranchise\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Data\TreeFactory
     */
    protected $treeFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * Topmenu constructor.
     *
     * @param \Magiccart\Shopfranchise\Helper\Data $helper
     * @param \Magento\Framework\Data\TreeFactory $treeFactory
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\Data\TreeFactory $treeFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magiccart\Shopfranchise\Helper\Data $helper
    ) {
        $this->treeFactory = $treeFactory;
        $this->request = $request;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     *
     * @return array
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) 
    {
        if(!$this->helper->getConfigModule('general/enabled') || !$this->helper->getConfigModule('general/topmenulink')) return;
        $subject->getMenu()->addChild(
                new Node(
                    $this->getFranchiseMenu(),
                    'id',
                    $this->treeFactory->create()
                )
            );
        return [$outermostClass, $childrenWrapClass, $limit];
    }

    /**
     * @return array
     */
    private function getFranchiseMenu()
    {
        return [
            'name'       => __('Franchise'),
            'id'         => 'shopfranchise-node',
            'url'        => $this->helper->getUrlRouter()
        ];
    }
}
