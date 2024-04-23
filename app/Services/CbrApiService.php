<?php

namespace App\Services;

use App\Models\Currency;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CbrApiService
{
    protected  $apiUrl;
    protected  $retryAttempts;
    protected  $retryDelay;

    public function __construct()
    {
        // URL для запроса к CBR_API_URL
        $this->apiUrl = config('services.cbr.api_url', '');
        $this->retryAttempts = config('services.cbr.retry_attempts', '');
        $this->retryDelay = config('services.cbr.retry_delay', '');
    }

    /**
     * Получить данные с CBR API.
     *
     * @return array|null
     */
    public function getExchangeRates(): ?array
    {
        $retryAttempts =  $this->retryAttempts;
        $retryDelay =  $this->retryDelay;

        for ($attempt = 1; $attempt <= $retryAttempts; $attempt++) {
            try {
                $client = new Client();
                $response = $client->request('GET', $this->apiUrl);


                if ($response->getStatusCode() === 200) {
                    $xmlData = $response->getBody()->getContents();
                    return Currency::createByArray($this->parseExchangeRatesFromXml($xmlData));
                }
            } catch (GuzzleException $e) {
                // Ошибка при запросе к API


                // Повторяем запрос через указанное время задержки
                if ($attempt < $retryAttempts) {
                    usleep($retryDelay * 1000);
                }
            } catch (Exception $e) {
                // Обработка других исключений

            }
        }

        // В случае неудачи после всех попыток
        return null;
    }

    /**
     * Распарсить XML данные с курсами валют.
     *
     * @param string $xmlData
     * @return array
     */
    protected function parseExchangeRatesFromXml($xmlData)
    {
        $xml = simplexml_load_string($xmlData);
        $json = json_encode($xml);
        $data = json_decode($json, true);

        return $data['Valute'];
    }



}
