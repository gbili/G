<?php
class   G_Country_Normalizer_Adapter_Objects_Austria extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/\\p{L}\\p{M}*e?sterreich|Au(?:stria|triche)/i';
	protected $_langISO = array(G_International_LangISO::DE);
}