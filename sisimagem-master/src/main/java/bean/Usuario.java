package bean;

public class Usuario {
	private String id;
	private String usuario;
	private String senha;
	private String grupo;
	private String bloqueio;

	public void setUsuario(String usuario) {
		if (usuario == null) {
			this.usuario = "";
		} else {
			this.usuario = usuario;
		}
	}

	public String getUsuario() {
		if (usuario == null) {
			return "";
		}
		return usuario;
	}

	public void setSenha(String senha) {
		if (senha == null) {
			this.senha = "";
		} else {
			this.senha = senha;
		}
	}

	public String getSenha() {
		if (senha == null) {
			return "";
		}
		return senha;
	}

	public void setGrupo(String grupo) {
		if (grupo == null) {
			this.grupo = "";
		} else {
			this.grupo = grupo;
		}
	}

	public String getGrupo() {
		if (grupo == null) {
			return "";
		}
		return grupo;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getId() {
		return id;
	}

	public void setBloqueio(String bloqueio) {
		this.bloqueio = bloqueio;
	}

	public String getBloqueio() {
		if (bloqueio == null) {
			return "";
		}

		return bloqueio.trim();
	}
}
