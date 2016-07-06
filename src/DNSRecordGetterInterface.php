<?php
/**
 *
 * @author Mikael Peigney
 */

namespace PIDI\SPFCheck;


interface DNSRecordGetterInterface
{
    public function getSPFRecordForDomain($domain);
    public function resolveA($domain);
    public function resolveMx($domain);
    public function resolvePtr($ipAddress);
    public function exists($domain);
    public function resetRequestCount();
    public function countRequest();
}