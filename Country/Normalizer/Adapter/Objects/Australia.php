<?php
class   G_Country_Normalizer_Adapter_Objects_Australia
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/Austr\\p{L}\\p{M}*li(?:a|en)/i';
	protected $_langISO = array(G_International_LangISO::EN);
}