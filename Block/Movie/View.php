<?php

namespace Infobase\CustomerMovie\Block\Movie;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Request\Http;
use Infobase\CustomerMovie\Model\MovieFactory;
use Infobase\CustomerMovie\Helper\Config;
use Magento\Framework\Exception\LocalizedException;

class View extends Template
{

    /**
     * @var string $_template
     */
    protected $_template = 'Infobase_CustomerMovie::view.phtml';

    /**
     * @var MovieFactory $movieFactory
     */
    protected $movieFactory;

    /**
     * @var Http $request
     */
    protected $request;

    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @param Template\Context $context
     * @param Http $request
     * @param MovieFactory $movieFactory
     * @param Config $config
     */
    public function __construct(
        Template\Context $context,
        Http $request,
        MovieFactory $movieFactory,
        Config $config
    ){
        $this->request = $request;
        $this->movieFactory = $movieFactory;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Method to get data movie by id
     * @return \Infobase\CustomerMovie\Model\Movie
     */
    public function getMovieToView() {
        $movieId = $this->request->getParam('movie_id');

        try {
            $movieCollection = $this->movieFactory->create()->load($movieId);
            return $movieCollection;
        } catch (\Exception $e) {
            $this->_logger->debug(__('Error: more details: ') . $e->getMessage());
            throw new LocalizedException(__("An error occurred contact support"));
        }
    }

    /**
     * Method to create url for using embed in iframe.
     * @param string $url
     * @return string
     */
    public function getEmbedUrl($url) {
        $code = null;
        $explode = explode('/', $url);
        foreach ($explode as $item) {
            if (strpos($item,'watch?') !== false) {
                $codeExplode = explode('=', $item);
                $code = $codeExplode[1];
            }
        }
        return 'https://youtube.com/embed/'.$code;
    }

    /**
     * Method to get if module is enabled.
     * @return boolean
     * @throws \Exception
     */
    public function moduleIsEnable() {
        return $this->config->isModuleEnabled();
    }

    /**
     * Method to format date in brazilian default
     * @param $date
     * @return string
     */
    public function convertDate($date)
    {
        $dateReturn = new \DateTime($date);

        return $dateReturn->format('d/m/Y');

    }

}
