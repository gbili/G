<?php
class   G_Country_Normalizer_Adapter_Objects_Belgium 
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/B\\p{L}\\p{M}*lgi(?:um|que|ca|en)/i';
	protected $_langISO = array(G_International_LangISO::FR);
}