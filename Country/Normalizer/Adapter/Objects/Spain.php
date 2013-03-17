<?php
class   G_Country_Normalizer_Adapter_Objects_Spain 
extends   G_Country_Normalizer_Adapter_Objects_Abstract
{
	protected $_regex = '/Espa\\p{L}\\p{M}*[ae]|Spa(?:nien|gna)|Spain/i';
	protected $_langISO = array(G_International_LangISO::ES,
								G_International_LangISO::CA,
								G_International_LangISO::GL,
								G_International_LangISO::EU);
}