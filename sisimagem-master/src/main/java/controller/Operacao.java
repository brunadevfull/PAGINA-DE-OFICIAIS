package controller;

import javax.servlet.http.HttpServletRequest;




/**
 * Esta classe define um m�todo abstrato executar que dever� ser implementado
 * nas subclasses concretas
 */
public abstract class Operacao {
	private String proxPagina = "/erro.jsp?msg=Opera��o sem redirecionamento";
    
	
    
	
	
	public Operacao() {
		
			
	}

	public Operacao(String pagina) {
		this.proxPagina = pagina;
	}

	// este m�todo deve ser implementado pelas opera��es filhas

	public abstract void executar(HttpServletRequest request) throws OperacaoException;

	public String getProxPagina() {
		return proxPagina;
	}

	public void setProxPagina(String novaPagina) {
		proxPagina = novaPagina;


	}
}