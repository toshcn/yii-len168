<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 *
 */

namespace common\components;

use Yii;
use yii\web\UrlManager;
use yii\base\InvalidConfigException;

/**
 * 多子域url生成组件 继承自yii\web\UrlManager
 *
 * You can modify its configuration by adding an array to your application config under `components`
 * as it is shown in the following example:
 * ~~~
 * 'urlManager' => [
 *     'class' => 'common\components\MutilpleDomainUrlManager',
 *     'hosts' => [//子域数组
 *          'backend' => '//' . DOMAIN_BACKEND,
 *          'api' => '//' . DOMAIN_API,
 *          'user' => '//' . DOMAIN_USER_CENTER,
 *          'login' => '//' . DOMAIN_LOGIN,
 *      ],
 *      'enablePrettyUrl' => true,
 *      'showScriptName' => false,
 *      // ...
 * ]
 * ~~~
 * @author xiaohao <toshcn@foxmail.com>
 * @version 0.1
 */
class MutilpleDomainUrlManager extends UrlManager
{
    /**
     * @var array 子域数组
     */
    public $hosts = [];

    /**
     * 创建Url地址 可指定子域名
     * 重构父类的createUrl方法
     *
     * @param  string|array  $params see parent createUrl()
     * @param  boolean|string $domain 子域名 如果不提供此参数生成的Url为相对地址
     * @return string the created URL
     * @see parent createUrl()
     */
    public function createUrl($params, $domain = false)
    {
        $baseUrl = $this->getBaseUrl();
        if ($domain) {
            if (isset($this->hosts[$domain])) {
                $this->setBaseUrl($this->hosts[$domain]);
            } else {
                throw new InvalidConfigException('Please configure UrlManager of domain "' . $domain . '".');
            }
        }

        $url = parent::createUrl($params);
        if ($domain && !$this->enablePrettyUrl) {
            $url = $this->getBaseUrl() . $url;
        }
        $this->setBaseUrl($baseUrl);
        return $url;
    }

    /**
     * Creates an absolute URL using the given route and query parameters.
     *
     * This method prepends the URL created by [[createUrl()]] with the [[hostInfo]].
     *
     * Note that unlike [[\yii\helpers\Url::toRoute()]], this method always treats the given route
     * as an absolute route.
     *
     * @param string|array $params use a string to represent a route (e.g. `site/index`),
     * or an array to represent a route with query parameters (e.g. `['site/index', 'param1' => 'value1']`).
     * @param string $scheme the scheme to use for the url (either `http` or `https`). If not specified
     * the scheme of the current request will be used.
     * @param string $domain 子域名标识
     * @return string the created URL
     * @see parent createAbsoluteUrl()
     */
    public function createAbsoluteUrl($params, $scheme = null, $domain = false)
    {
        $url = $this->createUrl($params, $domain);
        if (strpos($url, '://') === false) {
            if ($domain && isset($this->hosts[$domain])) {
                $url = $this->hosts[$domain] . $url;
            } else {
                $url = $this->getHostInfo() . $url;
            }
        }
        if (is_string($scheme) && ($pos = strpos($url, '://')) !== false) {
            $url = $scheme . substr($url, $pos);
        }
        return $url;
    }
}
