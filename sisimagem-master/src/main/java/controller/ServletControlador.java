package controller;

import java.io.IOException;
import java.util.HashMap;


import javax.servlet.RequestDispatcher;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;


//import org.apache.catalina.Session;
 
public class ServletControlador extends HttpServlet {
	private static final long serialVersionUID = 1L;

	private static final Logger log = LoggerFactory.getLogger(ServletControlador.class);
    // O conjunto de opera��es dispon�veis neste controlador fica
    // armazenado em um HashMap
	@SuppressWarnings("rawtypes")
	private final HashMap mapOperacoes;

    public ServletControlador() {
        this.mapOperacoes = new HashMap();
    }

	/**
	 * Este m�todo � usado para cadastrar as opera��es do controlador.A cada
 opera��o associa um nome no HashMap que ser� usado mais tarde para
 realizar uma busca
     * @throws javax.servlet.ServletException
	 */
        @Override
	public void init(ServletConfig config) throws ServletException {
		super.init(config);
		// incluirDocumentoPapem42
		mapOperacoes.put("login", new OperacaoLogin());
		mapOperacoes.put("alterarUsuario", new OperacaoAlterarUsuario());
		mapOperacoes.put("alterarSenha", new OperacaoAlterarSenha());
		mapOperacoes.put("excluirUsuario", new OperacaoExcluirUsuario());
		mapOperacoes.put("incluirUsuario", new OperacaoIncluirUsuario());
		mapOperacoes.put("detalharUsuario", new OperacaoDetalharUsuario());
		mapOperacoes.put("pesquisarUsuario", new OperacaoPesquisarUsuario());
		mapOperacoes.put("pesquisarDocumento", new OperacaoPesquisarDocumento());
		mapOperacoes.put("detalharDocumento", new OperacaoDetalharDocumento());
		// inserir documento
		mapOperacoes.put("incluirDocumento", new OperacaoIncluirDocumento());
		// scanear documento
		mapOperacoes.put("scanearDocumento", new OperacaoScanearDocumento());
		// localizar documento
		mapOperacoes.put("localizarDocumento", new OperacaoLocalizarDocumento());
		// encerra sess�o
		mapOperacoes.put("encerraSessao", new OperacaoEncerraSessao());
		// gera RecordId
		mapOperacoes.put("buscaProximoRecordId", new OperacaoBuscarRecordId());
		// incluirAnexoPapem41
		mapOperacoes.put("incluirAnexoPapem41", new OperacaoIncluirAnexoPapem41());
		// incluirDocumentoRespostaPapem42
		mapOperacoes.put("incluirDocumentoRespostaPapem42", new OperacaoIncluirDocumentoRespostaPapem42());
		// incluirDocumentoPapem42
		mapOperacoes.put("incluirDocumentoPapem42", new OperacaoIncluirDocumentoPapem42());
	}

        @Override
	public void service(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

		log.debug("entrou no servlet");
		log.debug("Inicio verifcacao de sessao");

		String strOp = request.getParameter("cmd");
		Operacao operacao = null;

		if (strOp == null) {
			log.debug("operacao null");
			redireciona("../views/erro.jsp?msg=Atividade n�o encontrada", request, response);
		} else {
			// Tenta localizar a atividade correspondente ao
			// comando recebido e execut�-lo
			try {

				// oper��o diferente de login, verificar se usu�rio est�
				// autenticado
				if (!strOp.equals("login")) {
					// Verifica��o de sess�o de usu�rio

					if (request.getSession(false).getAttribute("usuario") == null) {
						log.debug("session n�o criada = null");
						redireciona("../views/erro.jsp?mensagem=Usu�rio n�o Logado na aplica��o", request, response);
						return;
					} // end do if getSession
				}
				log.debug("realizar� a operacao " + strOp);
				operacao = getOperacao(strOp);
				operacao.executar(request);
				// caso esteja tudo certo, redireciona para a p�gina apropriada
				log.debug("redireciona a pagina");
				this.redireciona(operacao.getProxPagina(), request, response);
			} catch (OperacaoException e) {
				// redireciona para a p�gina de erro
				this.redireciona("../views/erro.jsp?mensagem=" + e.getMessage(), request, response);
			} // end do catch
				// }//end do else if Login
		} // end else strOp is null
	}

	// M�todo utilizado para localizar uma opera��o no HashMap
	public Operacao getOperacao(String strOp) throws OperacaoException {
		if (strOp == null) {
			throw new OperacaoException("String null recebida");
		}
		Operacao objOp = (Operacao) mapOperacoes.get(strOp);
		if (objOp == null) {
			throw new OperacaoException("Opera��o n�o definida");
		} else
			return objOp;
	}

	private void redireciona(String pagina, HttpServletRequest request, HttpServletResponse response)
			throws IOException, ServletException {
		// Transfere o controle para a p�gina especificada na string [pagina]
		log.debug(pagina);
		RequestDispatcher rd = request.getRequestDispatcher(pagina);
		rd.forward(request, response);
	}

}