<?php

namespace Wbcom\Whatsapp\Ui\Component\Listing\Columns\Chat;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    const URL_PATH_VISITORS_WHATSAPP_DELETE = 'wbcomwhatsapp/user/delete';
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Actions constructor.
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['id'])) {
                    $item[$this->getData('name')] = [
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_VISITORS_WHATSAPP_DELETE,
                                [
                                    'id' => $item['id']
                                ]
                            ),
                            'label' => __('Remove'),
                            'confirm' => [
                                'title' => __('Remove "${ $.$data.customer_name }"'),
                                'message' => __('Are you sure wan\'t to delete "${ $.$data.customer_name }" ?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
