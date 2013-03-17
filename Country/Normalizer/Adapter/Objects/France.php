<?php
class   G_Country_Normalizer_Adapter_Objects_France extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/Fran\\p{L}\\p{M}*(?:ia|[ea]|kreich)/i';
	protected $_langISO = array(G_International_LangISO::FR);
}