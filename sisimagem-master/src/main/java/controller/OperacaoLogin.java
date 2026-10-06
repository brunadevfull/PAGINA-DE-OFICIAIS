
package controller;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import utilitaria.Whirlpool;


import bean.Usuario;

import model.DAOTrim;

public class OperacaoLogin extends Operacao{

	private static Logger log = LoggerFactory.getLogger(OperacaoLogin.class);
	
	@Override
	public void executar(HttpServletRequest request) throws OperacaoException {
		// pega os parametros do request
		
		try{
			Usuario usuario = new Usuario();
			usuario.setUsuario(request.getParameter("usuario"));
			String senha = Whirlpool.criptografaSenha(request.getParameter("senha"));
			String senhaClara = request.getParameter("senha");
			int senhaInvalida = 0;
			try{
				
				 senhaInvalida = (Integer) request.getSession().getAttribute("senhaInvalida");
			}catch(NullPointerException e){
				log.debug("Ainda nao tinha senha invalida");
				
			}
			DAOTrim daoTrim = new DAOTrim();
			//daoTrim.obterConexao();
			daoTrim.obterConexaoDesenv();
			usuario = daoTrim.validaUsuarioSenha(usuario);
			if( usuario != null && usuario.getSenha().equals(senha) ){
				request.getSession().setAttribute("usuario", usuario);
				request.getSession().setAttribute("grupo", usuario.getGrupo());
				if(senhaClara.equals("marinha")){
					super.setProxPagina("../views/alterarSenha.jsp");
				}else{
					//update lclocation para data último acesso
					daoTrim.alterarUsuarioUltimoAcesso(usuario);
					super.setProxPagina("../views/index.jsp");
				}
				
			}else {
				
				senhaInvalida ++; 
				log.debug(" senha inválida - testa bloqueio");
				if(senhaInvalida == 5){
					
					if( usuario != null){ 
						// bloqueia usuario
						daoTrim.bloquearUsuario(usuario);
					}
					log.debug("teria que bloquear usuario");
					request.setAttribute("mensagem", "Usuário Bloqueado");
				}else{
					log.debug(" senha inválida - volta pra login");
					request.getSession().setAttribute("senhaInvalida", senhaInvalida);
					request.setAttribute("mensagem", "Usuário e/ou senha inválida.");
				}
				super.setProxPagina("../views/login.jsp");
			}
		}
			
		catch(Exception e)
		{
			log.debug("exception");
			request.setAttribute("mensagem", "Falha na execução da operação");
			super.setProxPagina("../views/erro.jsp");
			//throw new OperacaoException(e.getMessage());
		}
	} 
}