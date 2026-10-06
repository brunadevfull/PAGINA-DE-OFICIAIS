package utilitaria;

import java.io.IOException;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import controller.OperacaoIncluirDocumento;

public class LoginFilter implements Filter {

	private static Logger log = LoggerFactory.getLogger(LoginFilter.class);
	private String codigoUsuario = null;

	private String senhaUsuario, senhaCriptografada;

	private FilterConfig config;

	@Override
	public void destroy() {
		// TODO Auto-generated method stub
	}

	@Override
	public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain)
			throws IOException, ServletException {
		log.debug("entrou!!!!!");

		HttpServletRequest httpRequest = (HttpServletRequest) request;
		HttpServletResponse httpResponse = (HttpServletResponse) response;
		HttpSession sessao = httpRequest.getSession();

		String erro = "";
		log.debug("Entrou no filter");

		if ((((HttpServletRequest) request).getSession(false).getAttribute("usuario") == null)) {
			log.debug("sem session de usuario. ERRO!");
			erro = "Usu�rio n�o logado no sistema!";
			request.setAttribute("mensagem", "Realize o login para ter acesso ao sistema.");
			request.getRequestDispatcher("../views/erro.jsp").forward(request, httpResponse);
			return;
		} else {
			log.debug("Usu�rio logado no sistema! Com permiss�o!");
			// erro="Usu�rio n�o logado!";
		} // end do if getSession

		log.debug("vai para o chain - com session");

		chain.doFilter(request, httpResponse);

	}

	@Override
	public void init(FilterConfig arg0) throws ServletException {
		// TODO Auto-generated method stub
		log.debug("iniciou o filter!!!!!");
		this.config = config;
	}

}