<?php
class   G_Country_Normalizer_Adapter_Objects_Italy 
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/(?:It\\p{L}\\p{M}*l[yi](?:a|(?:en))?)/i';
	protected $_langISO = array(G_International_LangISO::IT);
}