<?php
class   G_Country_Normalizer_Adapter_Objects_Netherlands 
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/Pays-Bas|Pa\\p{L}\\p{M}*ses-Ba(?:j|ix)os|Paesi-Bassi|Niederlande|Netherlands/i';
	protected $_langISO = array(G_International_LangISO::NL);
}