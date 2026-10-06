package controller;

import java.util.List;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import bean.Usuario;

public class OperacaoDetalharUsuario extends Operacao {

	private static Logger log = LoggerFactory.getLogger(OperacaoDetalharUsuario.class);
	
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request
		HttpSession session = request.getSession();

		List<Usuario> listaUsuario = (List<Usuario>) session.getAttribute("listaUsuario");
		String idUsuario = request.getParameter("idUsuario");
		Usuario usuario = listaUsuario.get(Integer.parseInt(idUsuario));
		log.debug("usuario da session:" + usuario.getUsuario());
		log.debug("	grupo:" + usuario.getGrupo());

		session.setAttribute("usuarioDetalhe", usuario);
		super.setProxPagina("../views/detalharUsuario.jsp");

	}
}