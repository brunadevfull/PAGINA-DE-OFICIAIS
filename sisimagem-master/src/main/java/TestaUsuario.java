import bean.Usuario;
import model.DAOTrim;
import utilitaria.Whirlpool;

public class TestaUsuario {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		DAOTrim daoTrim = new DAOTrim();
		daoTrim.obterConexaoDesenv();
		Usuario usuario = new Usuario();
		usuario.setUsuario("34");
		usuario.setSenha(Whirlpool.criptografaSenha("marinha"));
		System.out.println(usuario.getSenha());
		// System.out.println(daoTrim.validaUsuarioSenha(usuario));

	}

}
