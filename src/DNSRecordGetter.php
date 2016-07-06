<?php
/**
 *
 * @author Mikael Peigney
 */

namespace PIDI\SPFCheck;


use PIDI\SPFCheck\Exception\DNSLookupException;
use PIDI\SPFCheck\Exception\DNSLookupLimitReachedException;

class DNSRecordGetter implements DNSRecordGetterInterface
{
    protected $requestCount = 0;

    /**
     * @param $domain string The domain to get SPF record
     * @return string|false The SPF record, or false if there is no SPF record
     * @throws DNSLookupException
     */
    public function getSPFRecordForDomain($domain)
    {
        $records = dns_get_record($domain, DNS_TXT | DNS_SOA);
        if (false === $records || (is_array($records) && empty($records))) {
            throw new DNSLookupException;
        }

        foreach ($records as $record) {
            if (array_key_exists('txt', $record)) {
                $txt = $record['txt'];
                if (stripos($txt, 'v=spf1') === 0) {
                    return $txt;
                }
            }
        }

        return false;
    }

    public function resolveA($domain)
    {
        $records = dns_get_record($domain, DNS_A | DNS_AAAA);
        if (false === $records || (is_array($records) && empty($records))) {
            throw new DNSLookupException;
        }

        $addresses = [];

        foreach ($records as $record) {
            if (array_key_exists('a', $record)) {
                $addresses[] = $record['a'];
            } elseif (array_key_exists('aaaa', $record)) {
                $addresses[] = $record['aaaa'];
            }
        }

        return $addresses;
    }

    public function resolveMx($domain)
    {
        $records = dns_get_record($domain, DNS_MX);
        if (false === $records || (is_array($records) && empty($records))) {
            throw new DNSLookupException;
        }

        $addresses = [];

        foreach ($records as $record) {
            if (array_key_exists('mx', $record)) {
                $addresses[] = $record['mx'];
            }
        }

        return $addresses;
    }

    public function resolvePtr($ipAddress)
    {
        // PHP does not seem to be able to get multiple PTR?
        return [gethostbyaddr($ipAddress)];
    }

    public function exists($domain)
    {
        return checkdnsrr($domain, 'A');
    }

    public function resetRequestCount()
    {
        $this->requestCount = 0;
    }

    public function countRequest()
    {
        if(++$this->requestCount == 10) {
            throw new DNSLookupLimitReachedException();
        }
    }
}